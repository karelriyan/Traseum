<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KategoriPengeluaran extends Model
{
    use HasUlids, HasFactory, LogsActivity;

    protected $fillable = ['name','text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('kategori_pengeluaran')
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori Pengeluaran has been {$eventName}");
    }
    
    protected $table = 'kategori_pengeluaran';
    protected $guarded = ['id'];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'nama_pengeluaran');
    }
}
