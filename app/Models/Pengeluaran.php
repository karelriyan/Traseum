<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Pengeluaran extends Model
{
    use HasUlids, SoftDeletes, HasFactory;
    protected $table = 'pengeluaran';
    protected $guarded = ['id'];
    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
    public function kategoriPengeluaran()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_pengeluaran_id');
    }

    public static function booted(): void
    {
        static::creating(function ($pengeluaran) {
            if (!$pengeluaran->user_id && Auth::check()) {
                $pengeluaran->user_id = Auth::id();
            }

            $defaultRekening = Rekening::where('no_rekening', '00000000')->first();

            if ($defaultRekening) {
                $pengeluaran->rekening_id = $defaultRekening->id;
            }


        });

        static::created(function ($pengeluaran) {
            // Kurangi saldo dan poin ke rekening nasabah
            if ($pengeluaran->rekening && $pengeluaran->nominal > 0) {
                $rekening = $pengeluaran->rekening;
                $rekening->balance -= $pengeluaran->nominal;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pengeluaran->nominal,
                    'type' => 'debit',
                    'description' => 'Pengurnagan Saldo Dari Pengeluaran',
                    'transactable_id' => $pengeluaran->id,
                    'transactable_type' => 'pengeluaran',
                ]);
            }
        });

        static::updated(function ($pengeluaran) {
            // Cek apakah total saldo/poin benar-benar berubah
            if ($pengeluaran->wasChanged('nominal')) {
                $rekening = $pengeluaran->rekening;

                if ($rekening) {
                    // Ambil nilai lama sebelum di-update
                    $saldoLama = $pengeluaran->getOriginal('nominal') ?? 0;

                    // Ambil nilai baru
                    $saldoBaru = $pengeluaran->nominal;

                    // Hitung selisihnya
                    $perubahanSaldo = $saldoBaru - $saldoLama;
                    ;

                    // Update saldo dan poin rekening dengan nilai selisih menggunakan increment/decrement
                    if ($perubahanSaldo > 0) {
                        $rekening->increment('balance', $perubahanSaldo);
                    } elseif ($perubahanSaldo < 0) {
                        $rekening->decrement('balance', abs($perubahanSaldo));
                    }

                    // Buat transaksi baru untuk mencatat perubahan ini
                    if ($perubahanSaldo != 0) {
                        \App\Models\SaldoTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanSaldo),
                            'type' => $perubahanSaldo > 0 ? 'debit' : 'credit',
                            'description' => 'Koreksi Saldo Dari Perubahan Data Pengeluaran',
                            'transactable_id' => $pengeluaran->id,
                            'transactable_type' => 'pengeluaran',
                        ]);
                    }
                }
            }
        });

        static::deleted(function ($pengeluaran) {
            if ($pengeluaran->rekening && $pengeluaran->nominal > 0) {
                $rekening = $pengeluaran->rekening;
                $rekening->balance += $pengeluaran->nominal;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pengeluaran->nominal,
                    'type' => 'credit',
                    'description' => 'Penambahan Saldo Dari Pembatalan pengeluaran',
                    'transactable_id' => $pengeluaran->id,
                    'transactable_type' => 'pengeluaran',
                ]);

            }
        });

        static::restored(function ($pengeluaran) {
            // Tambahkan saldo dan poin ke rekening nasabah
            if ($pengeluaran->rekening && $pengeluaran->nominal > 0) {
                $rekening = $pengeluaran->rekening;
                $rekening->balance -= $pengeluaran->nominal;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pengeluaran->nominal,
                    'type' => 'debit',
                    'description' => 'Pengurangan Saldo Dari pengeluaran yang Dihapus',
                    'transactable_id' => $pengeluaran->id,
                    'transactable_type' => 'pengeluaran',
                ]);

            }
        });
    }
}
