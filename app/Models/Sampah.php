<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Sampah extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'sampah';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($Sampah) {
            if (!$Sampah->user_id && Auth::check()) {
                $Sampah->user_id = Auth::id();
            }
        });
    }
}

