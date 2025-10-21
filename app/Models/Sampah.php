<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sampah extends Model
{
    use HasUlids, SoftDeletes, HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sampah')
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Sampah has been {$eventName}");
    }

    protected $fillable = ['name','text'];


    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'sampah';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SampahTransactions::class);
    }

    /**
     * Menghitung ulang total berat terkumpul dari transaksi dan menyimpannya.
     * Ini akan dipanggil oleh SampahTransactionsObserver.
     */
    public function updateBerat(): void
    {
        $masuk = $this->details()
            ->where('deleted_at', null)
            ->where('type', 'masuk')
            ->sum('berat');

        $keluar = $this->details()
            ->where('deleted_at', null)
            ->where('type', 'keluar')
            ->sum('berat');

        $this->total_berat_terkumpul = $masuk - $keluar;
        $this->saveQuietly();
    }

    public function recalculateTotalBerat(): void
    {
        $masuk = $this->details()
            ->where('deleted_at', null) // hanya yang aktif
            ->where('type', 'masuk')
            ->sum('berat');

        $keluar = $this->details()
            ->where('deleted_at', null)
            ->where('type', 'keluar')
            ->sum('berat');

        $this->total_berat_terkumpul = $masuk - $keluar;
        $this->saveQuietly();
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
