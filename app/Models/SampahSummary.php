<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SampahSummary extends Model
{
    use SoftDeletes, HasFactory;

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
        static::creating(function ($SampahSummary) {
            if (!$SampahSummary->user_id && Auth::check()) {
                $SampahSummary->user_id = Auth::id();
            }
        });
    }
}