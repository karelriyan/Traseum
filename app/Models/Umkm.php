<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Umkm extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'umkm';

    protected $guarded = [];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function postingan()
    {
        return $this->hasMany(PostinganUmkm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($Umkm) {
            if (!$Umkm->user_id && Auth::check()) {
                $Umkm->user_id = Auth::id();
            }
        });
    }
}