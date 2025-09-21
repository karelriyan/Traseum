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

        static::created(function ($detail) {
            if ($detail->sampah) {
                $detail->sampah->increment('total_berat_terkumpul', $detail->berat);
            }
        });

        static::updating(function ($detail) {
            if ($detail->isDirty('berat') && $detail->sampah) {
                $originalBerat = $detail->getOriginal('berat') ?? 0;
                $newBerat = $detail->berat;
                $diff = $newBerat - $originalBerat;
                $detail->sampah->increment('total_berat_terkumpul', $diff);
            }
        });

        static::deleted(function ($detail) {
            // This also handles soft deletes
            if ($detail->sampah) {
                $detail->sampah->decrement('total_berat_terkumpul', $detail->berat);
            }
        });

        static::restored(function ($detail) {
            if ($detail->sampah) {
                $detail->sampah->increment('total_berat_terkumpul', $detail->berat);
            }
        });
    }
}
