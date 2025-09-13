<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Pemasukan extends Model
{
    use HasUlids, SoftDeletes, HasFactory;
    protected $table = 'pemasukan';
    protected $guarded = ['id'];
    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function masuk()
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
    public function sumberPemasukan()
    {
        return $this->belongsTo(SumberPemasukan::class, 'sumber_pemasukan_id');
    }

    public static function booted(): void
    {
        static::creating(function ($pemasukan) {
            if (!$pemasukan->user_id && Auth::check()) {
                $pemasukan->user_id = Auth::id();
            }

            // Jika pemasukan BUKAN dari 'Sampah', maka set rekening default
            if ($pemasukan->metode_pembayaran !== 'Sampah') {
                $defaultRekening = Rekening::where('no_rekening', '00000000')->first();
                if ($defaultRekening) {
                    $pemasukan->rekening_id = $defaultRekening->id;
                }
            }
        });

        static::created(function ($pemasukan) {
            // JANGAN JALANKAN jika metode pembayaran adalah 'Sampah'
            if ($pemasukan->metode_pembayaran === 'Sampah') {
                return;
            }

            if ($pemasukan->rekening && $pemasukan->nominal > 0) {
                $rekening = $pemasukan->rekening;
                $rekening->increment('balance', $pemasukan->nominal);
                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pemasukan->nominal,
                    'type' => 'credit',
                    'description' => 'Penambahan saldo dari Pemasukan',
                    'transactable_id' => $pemasukan->id,
                    'transactable_type' => 'pemasukan',
                ]);
            }
        });

        static::updated(function ($pemasukan) {
            // JANGAN JALANKAN jika metode pembayaran adalah 'Sampah'
            if ($pemasukan->metode_pembayaran === 'Sampah') {
                return;
            }

            if ($pemasukan->wasChanged('nominal')) {
                $rekening = $pemasukan->rekening;
                if ($rekening) {
                    $saldoLama = $pemasukan->getOriginal('nominal') ?? 0;
                    $saldoBaru = $pemasukan->nominal;
                    $perubahanSaldo = $saldoBaru - $saldoLama;

                    if ($perubahanSaldo > 0) $rekening->increment('balance', $perubahanSaldo);
                    elseif ($perubahanSaldo < 0) $rekening->decrement('balance', abs($perubahanSaldo));

                    if ($perubahanSaldo != 0) {
                        \App\Models\SaldoTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanSaldo),
                            'type' => $perubahanSaldo > 0 ? 'credit' : 'debit',
                            'description' => 'Koreksi Saldo Dari Perubahan Data Pemasukan',
                            'transactable_id' => $pemasukan->id,
                            'transactable_type' => 'pemasukan',
                        ]);
                    }
                }
            }
        });

        static::deleted(function ($pemasukan) {
            // JANGAN JALANKAN jika metode pembayaran adalah 'Sampah'
            if ($pemasukan->metode_pembayaran === 'Sampah') {
                return;
            }

            if ($pemasukan->rekening && $pemasukan->nominal > 0) {
                $rekening = $pemasukan->rekening;
                $rekening->balance -= $pemasukan->nominal;
                $rekening->save();

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pemasukan->nominal,
                    'type' => 'debit',
                    'description' => 'Pengurangan Saldo Dari Pembatalan Pemasukan',
                    'transactable_id' => $pemasukan->id,
                    'transactable_type' => 'pemasukan',
                ]);
            }
        });

        static::restored(function ($pemasukan) {
            // JANGAN JALANKAN jika metode pembayaran adalah 'Sampah'
            if ($pemasukan->metode_pembayaran === 'Sampah') {
                return;
            }

            if ($pemasukan->rekening && $pemasukan->nominal > 0) {
                $rekening = $pemasukan->rekening;
                $rekening->balance += $pemasukan->nominal;
                $rekening->save();

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $pemasukan->nominal,
                    'type' => 'credit',
                    'description' => 'Pengembalian Saldo Dari Pemasukan yang Dihapus',
                    'transactable_id' => $pemasukan->id,
                    'transactable_type' => 'pemasukan',
                ]);
            }
        });
    }
}

