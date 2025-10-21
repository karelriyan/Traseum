<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SumberPemasukan extends Model
{
    use HasUlids, HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sumber_pemasukan')
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Sumber Pemasukan has been {$eventName}");
    }
    protected $fillable = ['name','text'];
    protected $table = 'sumber_pemasukan';
    protected $guarded = ['id'];

    public function pemasukan()
    {
        return $this->belongsTo(Pemasukan::class, 'nama_pemasukan');
    }

}
