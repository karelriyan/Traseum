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
        return $this->morphMany(SampahTransactions::class, 'transactable');
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
            $donationRekeningId = Rekening::where('no_rekening', '00000000')->value('id');
        }

        return $this->rekening_id === $donationRekeningId;
    }

    protected static function booted(): void
    {
        // Event ini berjalan SEBELUM data disimpan ke database
        static::creating(function ($setorSampah) {
            if (!$setorSampah->user_id && Auth::check()) {
                $setorSampah->user_id = Auth::id();
            }

            if (($setorSampah->jenis_setoran ?? null) === 'donasi') {
                $rekening = Rekening::firstOrCreate(
                    ['no_rekening' => '00000000'],
                    [
                        'nama' => 'Kas Bank Sampah (Donasi)',
                        'user_id' => User::first()->id ?? throw new \Exception('Sistem membutuhkan minimal satu user.'),
                        // default values
                        'nik' => '0000000000000000',
                        'no_kk' => '0000000000000000',
                        'gender' => 'Laki-laki',
                        'tanggal_lahir' => now()->subYears(20)->toDateString(),
                        'pendidikan' => 'TIDAK/BELUM SEKOLAH',
                        'dusun' => '0',
                        'rw' => '0',
                        'rt' => '0',
                    ]
                );
                $setorSampah->rekening_id = $rekening->id;

                // KUNCI UTAMA: Paksa total saldo dan poin menjadi 0 jika ini adalah donasi.
                // Logika ini menyederhanakan semua event lainnya.
                $setorSampah->total_saldo_dihasilkan = 0;
                $setorSampah->total_poin_dihasilkan = 0;
            }
        });

        // Event ini untuk menjaga integritas data JIKA ada yang mencoba mengedit donasi
        static::updating(function ($setorSampah) {
            if ($setorSampah->isDonation()) {
                $setorSampah->total_saldo_dihasilkan = 0;
                $setorSampah->total_poin_dihasilkan = 0;
            }
        });

        // Event ini berjalan SETELAH data disimpan
        static::created(function ($setorSampah) {
            // Hanya jalankan jika bukan donasi dan ada saldo yang dihasilkan
            if (!$setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
                if ($setorSampah->rekening) {
                    $rekening = $setorSampah->rekening;

                    // Update saldo rekening
                    $rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);

                    // Buat transaksi saldo untuk jejak audit
                    SaldoTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $setorSampah->total_saldo_dihasilkan,
                        'type' => 'credit',
                        'description' => 'Penambahan saldo dari setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => self::class,
                    ]);
                }
            }
        });

        static::updated(function ($setorSampah) {
            if ($setorSampah->wasChanged('total_saldo_dihasilkan')) {
                if ($setorSampah->rekening) {
                    $saldoLama = $setorSampah->getOriginal('total_saldo_dihasilkan') ?? 0;
                    $perubahanSaldo = $setorSampah->total_saldo_dihasilkan - $saldoLama;

                    // Log transaksi koreksi jika ada perubahan
                    if ($perubahanSaldo != 0) {
                        SaldoTransaction::create([
                            'rekening_id' => $setorSampah->rekening_id,
                            'amount' => abs($perubahanSaldo),
                            'type' => $perubahanSaldo > 0 ? 'credit' : 'debit',
                            'description' => 'Koreksi saldo dari perubahan data setor sampah',
                            'transactable_id' => $setorSampah->id,
                            'transactable_type' => self::class,
                        ]);
                    }
                }
            }
        });

        static::deleting(function ($setorSampah) {
            if (!$setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
                if ($setorSampah->rekening) {
                    $setorSampah->rekening->decrement('balance', $setorSampah->total_saldo_dihasilkan);

                    SaldoTransaction::create([
                        'rekening_id' => $setorSampah->rekening_id,
                        'amount' => $setorSampah->total_saldo_dihasilkan,
                        'type' => 'debit',
                        'description' => 'Pembatalan setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => self::class,
                    ]);
                }
            }
        });

        static::restored(function ($setorSampah) {
            if (!$setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
                if ($setorSampah->rekening) {
                    $setorSampah->rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);

                    SaldoTransaction::create([
                        'rekening_id' => $setorSampah->rekening_id,
                        'amount' => $setorSampah->total_saldo_dihasilkan,
                        'type' => 'credit',
                        'description' => 'Pemulihan data setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => self::class,
                    ]);
                }
            }
        });
    }
}
