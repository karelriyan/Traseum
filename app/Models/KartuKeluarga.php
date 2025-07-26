<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class KartuKeluarga extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $guarded = [];

    public function nasabah()
    {
        return $this->hasMany(Nasabah::class);
    }

    public function rekening()
    {
        return $this->hasOne(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($KartuKeluarga) {
            if (!$KartuKeluarga->user_id && Auth::check()) {
                $KartuKeluarga->user_id = Auth::id();
            }
        });
    }
}
