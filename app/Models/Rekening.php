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

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

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

    protected static function booted(): void
    {
        static::creating(function ($Rekening) {
            if (!$Rekening->user_id && Auth::check()) {
                $Rekening->user_id = Auth::id();
            }
        });
    }
}
