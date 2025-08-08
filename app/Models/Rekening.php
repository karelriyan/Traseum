<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Rekening extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'rekening';
    protected $guarded = [];

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

    /**
     * Menghitung saldo saat ini berdasarkan transaksi
     */
    public function getCurrentBalanceAttribute()
    {
        $credits = $this->saldoTransactions()
            ->where('type', 'credit')
            ->sum('amount');
            
        $debits = $this->saldoTransactions()
            ->where('type', 'debit')
            ->sum('amount');
            
        return $credits - $debits;
    }

    /**
     * Memeriksa apakah saldo mencukupi untuk penarikan
     */
    public function hasSufficientBalance($amount)
    {
        return $this->current_balance >= $amount;
    }

    /**
     * Mendapatkan saldo dalam format rupiah
     */
    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format($this->current_balance, 0, ',', '.');
    }

    protected static function booted(): void
    {
        static::creating(function ($Rekening) {
            if (!$Rekening->user_id && Auth::check()) {
                $Rekening->user_id = Auth::id();
            }
        });
    }
}
