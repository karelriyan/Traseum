<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SampahKeluar extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $guarded = [];

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
        static::creating(function ($SampahKeluar) {
            if (!$SampahKeluar->user_id && Auth::check()) {
                $SampahKeluar->user_id = Auth::id();
            }
        });
    }
}