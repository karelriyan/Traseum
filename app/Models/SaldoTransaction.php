<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SaldoTransaction extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'saldo_transactions';

    protected $guarded = [];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactable()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::creating(function ($SaldoTransaction) {
            if (!$SaldoTransaction->user_id && Auth::check()) {
                $SaldoTransaction->user_id = Auth::id();
            }
        });
    }
}
