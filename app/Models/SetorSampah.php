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

    /**
     * Helper function to check if this is a donation.
     * Caches the donation account ID for efficiency.
     */
    public function isDonation(): bool
    {
        static $donationRekeningId = null;

        if (is_null($donationRekeningId)) {
            $donationRekeningId = Rekening::where('no_rekening', '00000000')->value('id') ?? false;
        }

        return $this->rekening_id === $donationRekeningId;
    }

    protected static function booted(): void
    {
        static::creating(function ($setorSampah) {
            // Automatically set user_id if not present
            if (!$setorSampah->user_id && Auth::check()) {
                $setorSampah->user_id = Auth::id();
            }

            // If the transaction type is 'donasi', force it to use the donation account.
            // This will find the donation account or create it if it doesn't exist, preventing errors.
            if (($setorSampah->jenis_setoran ?? null) === 'donasi') {
                $donationUser = User::first(); // Get first user as owner
                if (!$donationUser) {
                    // This will prevent errors if no users exist in the system yet.
                    throw new \Exception('Tidak ada user di dalam sistem untuk dijadikan pemilik rekening donasi.');
                }

                $rekening = Rekening::firstOrCreate(
                    ['no_rekening' => '00000000'],
                    [
                        'nama' => 'Kas Bank Sampah (Donasi)',
                        'nik' => '0000000000000000',
                        'no_kk' => '0000000000000000',
                        'gender' => 'Laki-laki',
                        'tanggal_lahir' => now()->subYears(20)->toDateString(),
                        'pendidikan' => 'TIDAK/BELUM SEKOLAH',
                        'dusun' => '0',
                        'rw' => '0',
                        'rt' => '0',
                        'user_id' => $donationUser->id,
                    ]
                );
                $setorSampah->rekening_id = $rekening->id;
            }
        });

        static::created(function ($setorSampah) {
            // --- 1. SINKRONISASI KE PEMASUKAN JIKA DONASI ---
            if ($setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
                $sumber = SumberPemasukan::firstOrCreate(['nama_pemasukan' => 'Donasi']);

                Pemasukan::create([
                    'tanggal' => $setorSampah->created_at->toDateString(),
                    'nominal' => $setorSampah->total_saldo_dihasilkan,
                    'keterangan' => 'Donasi dari Setor Sampah ID: ' . $setorSampah->id,
                    'sumber_pemasukan_id' => $sumber->id,
                    'user_id' => $setorSampah->user_id,
                    'rekening_id' => $setorSampah->rekening_id, // Ini sekarang dijamin ada isinya
                    'masuk_id' => $setorSampah->id,
                    'masuk_type' => self::class,
                    'metode_pembayaran' => 'Sampah', // Penanda agar Pemasukan tidak trigger saldo
                ]);
            }

            // --- 2. LOGIKA SALDO & POIN REKENING ---
            if ($setorSampah->rekening && $setorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $setorSampah->rekening;

                $rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);
                $rekening->increment('points_balance', $setorSampah->total_poin_dihasilkan);

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Penambahan saldo dari setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                if ($setorSampah->total_poin_dihasilkan > 0) {
                    \App\Models\PoinTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $setorSampah->total_poin_dihasilkan,
                        'description' => 'Penambahan poin dari setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => 'setor_sampah',
                    ]);
                }
            }
        });

        static::updated(function ($setorSampah) {
            // --- 1. SINKRONISASI UPDATE KE PEMASUKAN JIKA DONASI ---
            if ($setorSampah->isDonation() && $setorSampah->wasChanged('total_saldo_dihasilkan')) {
                $pemasukan = Pemasukan::where('masuk_id', $setorSampah->id)
                    ->where('masuk_type', self::class)
                    ->first();

                if ($pemasukan) {
                    $pemasukan->update(['nominal' => $setorSampah->total_saldo_dihasilkan]);
                }
            }

            // --- 2. LOGIKA KOREKSI SALDO & POIN REKENING ---
            if ($setorSampah->wasChanged('total_saldo_dihasilkan') || $setorSampah->wasChanged('total_poin_dihasilkan')) {
                $rekening = $setorSampah->rekening;

                if ($rekening) {
                    $saldoLama = $setorSampah->getOriginal('total_saldo_dihasilkan') ?? 0;
                    $poinLama = $setorSampah->getOriginal('total_poin_dihasilkan') ?? 0;
                    $saldoBaru = $setorSampah->total_saldo_dihasilkan;
                    $poinBaru = $setorSampah->total_poin_dihasilkan;

                    $perubahanSaldo = $saldoBaru - $saldoLama;
                    $perubahanPoin = $poinBaru - $poinLama;

                    if ($perubahanSaldo > 0)
                        $rekening->increment('balance', $perubahanSaldo);
                    elseif ($perubahanSaldo < 0)
                        $rekening->decrement('balance', abs($perubahanSaldo));

                    if ($perubahanPoin > 0)
                        $rekening->increment('points_balance', $perubahanPoin);
                    elseif ($perubahanPoin < 0)
                        $rekening->decrement('points_balance', abs($perubahanPoin));

                    if ($perubahanSaldo != 0) {
                        \App\Models\SaldoTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanSaldo),
                            'type' => $perubahanSaldo > 0 ? 'credit' : 'debit',
                            'description' => 'Koreksi Saldo Dari Perubahan Data Setor Sampah',
                            'transactable_id' => $setorSampah->id,
                            'transactable_type' => 'setor_sampah',
                        ]);
                    }

                    if ($perubahanPoin != 0) {
                        \App\Models\PoinTransaction::create([
                            'rekening_id' => $rekening->id,
                            'amount' => abs($perubahanPoin),
                            'description' => 'Koreksi Poin Dari Perubahan Data Setor Sampah',
                            'transactable_id' => $setorSampah->id,
                            'transactable_type' => 'setor_sampah',
                        ]);
                    }
                }
            }
        });

        static::deleting(function ($setorSampah) {
            // Handle Saldo & Poin
            if ($setorSampah->rekening && $setorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $setorSampah->rekening;
                $rekening->decrement('balance', $setorSampah->total_saldo_dihasilkan);
                $rekening->decrement('points_balance', $setorSampah->total_poin_dihasilkan);

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan,
                    'type' => 'debit',
                    'description' => 'Pengurangan Saldo Dari Pembatalan Setor Sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                if ($setorSampah->total_poin_dihasilkan > 0) {
                    \App\Models\PoinTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $setorSampah->total_poin_dihasilkan,
                        'description' => 'Pengurangan Poin Dari Pembatalan Setor Sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => 'setor_sampah',
                    ]);
                }
            }

            // Handle sinkronisasi Pemasukan
            if ($setorSampah->isDonation()) {
                $query = Pemasukan::where('masuk_id', $setorSampah->id)->where('masuk_type', self::class);
                $setorSampah->isForceDeleting() ? $query->forceDelete() : $query->delete();
            }
        });

        static::restored(function ($setorSampah) {
            // Handle Saldo & Poin
            if ($setorSampah->rekening && $setorSampah->total_saldo_dihasilkan > 0) {
                $rekening = $setorSampah->rekening;
                $rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);
                $rekening->increment('points_balance', $setorSampah->total_poin_dihasilkan);

                \App\Models\SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Pengembalian Saldo Dari Setor Sampah yang Dihapus',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);

                if ($setorSampah->total_poin_dihasilkan > 0) {
                    \App\Models\PoinTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $setorSampah->total_poin_dihasilkan,
                        'description' => 'Pengembalian Poin Dari Setor Sampah yang Dihapus',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => 'setor_sampah',
                    ]);
                }
            }

            // Handle sinkronisasi Pemasukan
            if ($setorSampah->isDonation()) {
                Pemasukan::where('masuk_id', $setorSampah->id)->where('masuk_type', self::class)->withTrashed()->restore();
            }
        });
    }
}

