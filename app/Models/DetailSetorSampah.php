<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DetailSetorSampah extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'detail_setor_sampah';
    protected $guarded = [];

    public function setorSampah()
    {
        return $this->belongsTo(SetorSampah::class);
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
        static::creating(function ($DetailSetorSampah) {
            if (!$DetailSetorSampah->user_id && Auth::check()) {
                $DetailSetorSampah->user_id = Auth::id();
            }
        });
    }
}
