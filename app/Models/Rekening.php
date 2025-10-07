<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Rekening extends Authenticatable
{
    use HasUlids, SoftDeletes, HasFactory, HasApiTokens;

    protected $table = 'rekening';

     protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

     protected $fillable = [
        'no_rekening',
        'nama',
        'nik',
        'no_kk',
        'telepon',
        'balance',
        'tanggal_lahir',
        'points_balance',
        'status_pegadaian',
        'status_lengkap',
        'status_desa',
        'user_id',
    ];

    protected $hidden = [
        'remember_token', // kalau nanti ditambah
    ];

    protected $casts = [
        'status_desa' => 'boolean', // <-- TAMBAHKAN BARIS INI
        'status_pegadaian' => 'boolean',
        'status_lengkap' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['points_balance', 'formatted_balance'];

    public function saldoTransactions()
    {
        return $this->hasMany(SaldoTransaction::class);
    }

    public function poinTransactions()
    {
        return $this->hasMany(PoinTransaction::class);
    }

    public function setorSampah()
    {
        return $this->hasMany(SetorSampah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateBalance(): void
    {
        $credit = $this->saldoTransactions()
            ->where('deleted_at', null)
            ->where('type', 'credit')
            ->sum('amount');

        $debit = $this->saldoTransactions()
            ->where('deleted_at', null)
            ->where('type', 'debit')
            ->sum('amount');

        $this->balance = $credit - $debit;
        $this->saveQuietly(); // biar tidak trigger event save lagi
    }


    /**
     * Menghitung ulang total saldo dari transaksi dan menyimpannya.
     * Ini akan dipanggil oleh SaldoTransactionObserver.
     */
    public function recalculateBalance(): void
    {
        $credits = $this->saldoTransactions()->where('type', 'credit')->sum('amount');
        $debits = $this->saldoTransactions()->where('type', 'debit')->sum('amount');
        $this->balance = $credits - $debits;
        $this->saveQuietly(); // Simpan tanpa memicu event lain
    }

    // Hapus accessor `getCurrentBalanceAttribute` karena saldo sekarang disimpan di kolom `balance`.
    // Kita akan menggunakan nilai dari kolom `balance` secara langsung.

    /**
     * Menghitung saldo poin saat ini berdasarkan transaksi
     * Catatan: Poin transactions tidak memiliki kolom 'type', 
     * semua transaksi poin dianggap sebagai credit (penambahan)
     */
    public function getPointsBalanceAttribute()
    {
        return $this->poinTransactions()->sum('amount');
    }

    /**
     * Memeriksa apakah saldo mencukupi untuk penarikan
     */
    public function hasSufficientBalance($amount)
    {
        return $this->balance >= $amount;
    }

    /**
     * Mendapatkan saldo dalam format rupiah
     */
    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }


    // Method untuk menghitung dan mengatur status kelengkapan
    public function calculateAndSetStatusLengkap(): void
    {
        $requiredFields = [
            'nik',
            'no_kk',
            'tanggal_lahir',
            'pendidikan',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                $this->status_lengkap = false;
                return;
            }
        }

        if ($this->status_desa === false) {
            if (empty($this->dusun) || empty($this->rw) || empty($this->rt)) {
                $this->status_lengkap = false;
                return;
            }
        } elseif ($this->status_desa === true) {
            if (empty($this->alamat)) {
                $this->status_lengkap = false;
                return;
            }
        } else {
            $this->status_lengkap = true;
            return;
        }

        $this->status_lengkap = true;
    }

    protected static function booted(): void
    {
        static::creating(function ($Rekening) {
            if (!$Rekening->user_id && Auth::check()) {
                $Rekening->user_id = Auth::id();
            }

            if ($Rekening->balance > 0) {
                $Rekening->saldoTransactions()->create([
                    'amount' => $Rekening->balance,
                    'type' => 'credit',
                    'description' => 'Saldo Awal',
                    'transactable_id' => $Rekening->id,
                    'transactable_type' => '-',
                    'user_id' => $Rekening->user_id,
                ]);
            }
        });

        static::saving(function (Rekening $rekening) {
            // Panggil method untuk kalkulasi status
            $rekening->calculateAndSetStatusLengkap();
        });
    }
}
