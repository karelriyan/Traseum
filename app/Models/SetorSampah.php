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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (($model->jenis_setoran ?? null) === 'donasi' && empty($model->rekening_id)) {
                $rekening = Rekening::where('no_rekening', '00000000000000')->first();
                $model->rekening_id = $rekening?->id;
            }
        });

        static::creating(function ($SetorSampah) {
            if (!$SetorSampah->user_id && Auth::check()) {
                $SetorSampah->user_id = Auth::id();
            }
        });

        static::created(function ($SetorSampah) {
            // Tambahkan saldo dan poin ke rekening nasabah
            if ($SetorSampah->rekening && $SetorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $SetorSampah->rekening;
                
                // Update saldo di tabel rekening
                $rekening->increment('balance', $SetorSampah->total_saldo_dihasilkan);
                $rekening->increment('points_balance', $SetorSampah->total_poin_dihasilkan);

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Penambahan saldo dari setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                // Buat transaksi poin
                \App\Models\PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_poin_dihasilkan,
                    'description' => 'Penambahan poin dari setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }
        });

        static::updated(function ($setorSampah) {
            // Cek apakah total saldo/poin benar-benar berubah
            if ($setorSampah->wasChanged('total_saldo_dihasilkan') || $setorSampah->wasChanged('total_poin_dihasilkan')) {
                $rekening = $setorSampah->rekening;

                if ($rekening) {
                    // Ambil nilai lama sebelum di-update
                    $saldoLama = $setorSampah->getOriginal('total_saldo_dihasilkan') ?? 0;
                    $poinLama = $setorSampah->getOriginal('total_poin_dihasilkan') ?? 0;

                    // Ambil nilai baru
                    $saldoBaru = $setorSampah->total_saldo_dihasilkan;
                    $poinBaru = $setorSampah->total_poin_dihasilkan;

                    // Hitung selisihnya
                    $perubahanSaldo = $saldoBaru - $saldoLama;
                    $perubahanPoin = $poinBaru - $poinLama;

                    // Update saldo dan poin rekening dengan nilai selisih menggunakan increment/decrement
                    if ($perubahanSaldo > 0) {
                        $rekening->increment('balance', $perubahanSaldo);
                    } elseif ($perubahanSaldo < 0) {
                        $rekening->decrement('balance', abs($perubahanSaldo));
                    }
                    
                    if ($perubahanPoin > 0) {
                        $rekening->increment('points_balance', $perubahanPoin);
                    } elseif ($perubahanPoin < 0) {
                        $rekening->decrement('points_balance', abs($perubahanPoin));
                    }

                    // Buat transaksi baru untuk mencatat perubahan ini
                    if ($perubahanSaldo != 0) {
                        \App\Models\SaldoTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanSaldo),
                            'type' => $perubahanSaldo > 0 ? 'credit' : 'debit',
                            'description' => 'Koreksi saldo dari perubahan data setor sampah',
                            'transactable_id' => $setorSampah->id,
                            'transactable_type' => 'setor_sampah',
                        ]);
                    }

                    if ($perubahanPoin != 0) {
                        \App\Models\PoinTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanPoin),
                            'description' => 'Koreksi poin dari perubahan data setor sampah',
                            'transactable_id' => $setorSampah->id,
                            'transactable_type' => 'setor_sampah',
                        ]);
                    }
                }
            }
        });

        static::deleted(function ($SetorSampah) {
            if ($SetorSampah->rekening && $SetorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $SetorSampah->rekening;
                $rekening->balance -= $SetorSampah->total_saldo_dihasilkan;
                $rekening->points_balance -= $SetorSampah->total_poin_dihasilkan;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_saldo_dihasilkan,
                    'type' => 'debit',
                    'description' => 'Pengurangan saldo dari pembatalan setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                // Buat transaksi poin
                \App\Models\PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_poin_dihasilkan,
                    'description' => 'Pengurangan poin dari pembatalan setor sampah',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }
        });

        static::restored(function ($SetorSampah) {
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
                    'description' => 'Pengembalian saldo dari setor sampah yang terhapus',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                // Buat transaksi poin
                \App\Models\PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $SetorSampah->total_poin_dihasilkan,
                    'description' => 'Pengembalian poin dari setor sampah yang terhapus',
                    'transactable_id' => $SetorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }
        });
    }
}
