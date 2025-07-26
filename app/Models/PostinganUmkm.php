<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PostinganUmkm extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'postingan_umkm';

    protected $guarded = [];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($PostinganUmkm) {
            if (!$PostinganUmkm->user_id && Auth::check()) {
                $PostinganUmkm->user_id = Auth::id();
            }
        });
    }
}
