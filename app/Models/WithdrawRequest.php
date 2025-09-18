<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WithdrawRequest extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'withdraw_requests';

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'is_new_pegadaian_registration' => 'boolean',
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function saldoTransaction()
    {
        return $this->hasOne(SaldoTransaction::class, 'transactable_id')
            ->where('transactable_type', WithdrawRequest::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($withdrawRequest) {
            if (!$withdrawRequest->user_id && Auth::check()) {
                $withdrawRequest->user_id = Auth::id();
            }
        });

        static::created(function ($withdrawRequest) {
            // Cek apakah permintaan ini ditandai sebagai pendaftaran baru DAN nomor rekeningnya ada.
            if ($withdrawRequest->is_new_pegadaian_registration) {
                $rekening = $withdrawRequest->rekening;

                if ($rekening) {
                    // Update status dan nomor rekening di tabel induk (rekening).
                    $rekening->status_pegadaian = true;
                    $rekening->save();
                }
            }
        });

        static::restored(function ($withdrawRequest) {
            // Cek apakah permintaan ini ditandai sebagai pendaftaran baru DAN nomor rekeningnya ada.
            if ($withdrawRequest->is_new_pegadaian_registration) {
                $rekening = $withdrawRequest->rekening;

                if ($rekening) {
                    // Update status dan nomor rekening di tabel induk (rekening).
                    $rekening->status_pegadaian = true;
                    $rekening->save();
                }
            }
        });

        static::deleted(function ($withdrawRequest) {
            // Cek apakah permintaan ini ditandai sebagai pendaftaran baru DAN nomor rekeningnya ada.
            if ($withdrawRequest->is_new_pegadaian_registration) {
                $rekening = $withdrawRequest->rekening;

                if ($rekening) {
                    // Update status dan nomor rekening di tabel induk (rekening).
                    $rekening->status_pegadaian = false;
                    $rekening->save();
                }
            }
        });

        static::created(function ($withdrawRequest) {
            // Kurangi saldo dan poin ke rekening nasabah
            if ($withdrawRequest->rekening && $withdrawRequest->amount > 0) {
                $rekening = $withdrawRequest->rekening;
                $rekening->balance -= $withdrawRequest->amount;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $withdrawRequest->amount,
                    'type' => 'debit',
                    'description' => 'Penarikan Saldo',
                    'transactable_id' => $withdrawRequest->id,
                    'transactable_type' => 'tarik_saldo',
                ]);
            }
        });

        static::updated(function ($withdrawRequest) {
            // Cek apakah total saldo/poin benar-benar berubah
            if ($withdrawRequest->wasChanged('amount')) {
                $rekening = $withdrawRequest->rekening;

                if ($rekening) {
                    // Ambil nilai lama sebelum di-update
                    $amountLama = $withdrawRequest->getOriginal('amount') ?? 0;

                    // Ambil nilai baru
                    $amountBaru = $withdrawRequest->amount;

                    // Hitung selisihnya
                    $perubahanAmount = $amountBaru - $amountLama;

                    // Update saldo dan poin rekening dengan nilai selisih
                    $rekening->balance += $perubahanAmount;
                    $rekening->save();

                    // Buat transaksi baru untuk mencatat perubahan ini
                    if ($perubahanAmount != 0) {
                        \App\Models\SaldoTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanAmount),
                            'type' => $perubahanAmount > 0 ? 'debit' : 'credit',
                            'description' => 'Koreksi Saldo Dari Perubahan Data Penarikan Saldo',
                            'transactable_id' => $withdrawRequest->id,
                            'transactable_type' => 'setor_sampah',
                        ]);
                    }
                }
            }
        });

        static::deleted(function ($withdrawRequest) {
            if ($withdrawRequest->rekening && $withdrawRequest->amount > 0) {
                $rekening = $withdrawRequest->rekening;
                $rekening->balance += $withdrawRequest->amount;
                $rekening->save();

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $withdrawRequest->amount,
                    'type' => 'credit',
                    'description' => 'Penambahan Saldo dari Pembatalan Penarikan Saldo',
                    'transactable_id' => $withdrawRequest->id,
                    'transactable_type' => 'tarik_saldo',
                ]);
            }
        });

        static::restored(function ($withdrawRequest) {
            // Kurangi saldo dan poin ke rekening nasabah
            if ($withdrawRequest->rekening && $withdrawRequest->amount > 0) {
                $rekening = $withdrawRequest->rekening;
                $rekening->balance -= $withdrawRequest->amount;
                $rekening->save();

                // Buat transaksi saldo
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $withdrawRequest->amount,
                    'type' => 'debit',
                    'description' => 'Pengembalian Pengurangan Penarikan Saldo yang Dibatalkan',
                    'transactable_id' => $withdrawRequest->id,
                    'transactable_type' => 'tarik_saldo',
                ]);
            }
        });
    }
}
