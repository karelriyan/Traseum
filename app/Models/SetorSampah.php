<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SetorSampah extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'setor_sampah';

    protected $guarded = [];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function details()
    {
        return $this->hasMany(DetailSetorSampah::class);
    }

    public function sampah()
    {
        return $this->belongsTo(Sampah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($SetorSampah) {
            if (!$SetorSampah->user_id && Auth::check()) {
                $SetorSampah->user_id = Auth::id();
            }

            // Hitung total saldo dan poin berdasarkan sampah dan berat
            if ($SetorSampah->sampah && $SetorSampah->berat > 0) {
                $berat_kg_liter = $SetorSampah->berat / 1000;
                $SetorSampah->total_saldo_dihasilkan = $SetorSampah->sampah->saldo_per_kg * $berat_kg_liter;
                $SetorSampah->total_poin_dihasilkan = $SetorSampah->sampah->poin_per_kg * $berat_kg_liter;
            }
        });

        static::updating(function ($SetorSampah) {
            // Hitung ulang jika sampah atau berat berubah
            if ($SetorSampah->isDirty(['sampah_id', 'berat'])) {
                $sampah = $SetorSampah->sampah;
                if ($sampah && $SetorSampah->berat > 0) {
                    $berat_kg_liter = $SetorSampah->berat / 1000;
                    $SetorSampah->total_saldo_dihasilkan = $sampah->saldo_per_kg * $berat_kg_liter;
                    $SetorSampah->total_poin_dihasilkan = $sampah->poin_per_kg * $berat_kg_liter;
                }
            }
        });

        static::created(function ($SetorSampah) {
            // Tambahkan saldo dan poin ke rekening nasabah
            if ($SetorSampah->rekening && $SetorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $SetorSampah->rekening;
                $rekening->balance += $SetorSampah->total_saldo_dihasilkan;
                $rekening->points_balance += $SetorSampah->total_poin_dihasilkan;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Penambahan saldo dari setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                    'prosesor' => $withdrawRequest->user_id->name ?? 'System',
                ]);

                // Buat transaksi poin
                \App\Models\PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_poin_dihasilkan,
                    'description' => 'Penambahan poin dari setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                    'prosesor' => $withdrawRequest->user_id->name ?? 'System',
                ]);
            }
        });
    }
}
