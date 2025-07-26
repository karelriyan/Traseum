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

    protected $guarded = [];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function details()
    {
        return $this->hasMany(DetailSetorSampah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($SetorSampah) {
            if (!$SetorSampah->user_id && Auth::check()) {
                $SetorSampah->user_id = Auth::id();
            }
        });
    }
}