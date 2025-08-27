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

    protected $fillable = [
        'rekening_id',
        'user_id',
        'amount',
        'jenis',
        'catatan',
        'bank_name',
        'account_number',
        'account_holder_name',
        'notes',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
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
                    'description' => 'Penarikan saldo',
                    'transactable_id' => $withdrawRequest->id,
                    'transactable_type' => 'tarik_saldo',
                ]);
            }
        });
    }
}
