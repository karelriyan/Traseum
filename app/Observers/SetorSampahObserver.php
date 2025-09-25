<?php

namespace App\Observers;

use App\Models\SetorSampah;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SetorSampahObserver
{
    /**
     * Handle the SetorSampah "creating" event.
     */
    public function creating(SetorSampah $setorSampah): void
    {
        if (!$setorSampah->user_id && Auth::check()) {
            $setorSampah->user_id = Auth::id();
        }

        if (($setorSampah->jenis_setoran ?? null) === 'donasi') {
            $rekening = Rekening::firstOrCreate(
                ['no_rekening' => '00000000'],
                [
                    'nama' => 'Kas Bank Sampah (Donasi)',
                    'user_id' => User::first()->id ?? throw new \Exception('Sistem membutuhkan minimal satu user.'),
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
            $setorSampah->total_saldo_dihasilkan = 0;
            $setorSampah->total_poin_dihasilkan = 0;
        }
    }

    /**
     * Handle the SetorSampah "created" event.
     */
    public function created(SetorSampah $setorSampah): void
    {
        // Buat transaksi untuk setiap item detail
        foreach ($setorSampah->details as $detail) {
            // Buat transaksi berat (selalu, untuk setor dan donasi)
            $detail->sampah->details()->create([
                'type' => 'credit',
                'berat' => $detail->berat,
                'transactable_id' => $setorSampah->id,
                'transactable_type' => SetorSampah::class,
                'rekening_id' => $setorSampah->rekening_id,
                'user_id' => $setorSampah->user_id,
            ]);
        }

        // Buat transaksi saldo hanya jika bukan donasi dan ada saldo
        if (!$setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
            $setorSampah->rekening->saldoTransactions()->create([
                'amount' => $setorSampah->total_saldo_dihasilkan,
                'type' => 'credit',
                'description' => 'Setor Sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => SetorSampah::class,
                'user_id' => $setorSampah->user_id,
            ]);
        }
    }

    /**
     * Handle the SetorSampah "updating" event.
     */
    public function updating(SetorSampah $setorSampah): void
    {
        // Jika diubah menjadi donasi, hapus saldo
        if ($setorSampah->isDirty('jenis_setoran') && $setorSampah->jenis_setoran === 'donasi') {
            $setorSampah->total_saldo_dihasilkan = 0;
            $setorSampah->total_poin_dihasilkan = 0;
        }
    }

    /**
     * Handle the SetorSampah "deleted" event.
     */
    public function deleted(SetorSampah $setorSampah): void
    {
        // Hapus semua transaksi terkait
        $setorSampah->details()->delete();
        $setorSampah->rekening->saldoTransactions()->where('transactable_id', $setorSampah->id)->delete();
    }

    /**
     * Handle the SetorSampah "restored" event.
     */
    public function restored(SetorSampah $setorSampah): void
    {
        // Logika pemulihan bisa lebih kompleks, untuk saat ini kita bisa memicu recalculate
        // atau membuat ulang transaksi jika diperlukan.
        // Untuk simple, kita hapus lalu buat ulang.
        $this->deleted($setorSampah);
        $this->created($setorSampah);
    }
}