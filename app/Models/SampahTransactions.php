<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SampahTransactions extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'sampah_transactions';
    protected $guarded = [];

    protected $casts = [
        'berat' => 'decimal:2',
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }


    public function setorSampah()
    {
        return $this->morphTo(SetorSampah::class);
    }

    public function sampah()
    {
        return $this->belongsTo(Sampah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($detail) {
            if (!$detail->user_id && Auth::check()) {
                $detail->user_id = Auth::id();
            }
        });
    }
}
