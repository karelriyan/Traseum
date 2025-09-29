<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SampahKeluar extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'sampah_keluar';


    protected $guarded = [];

    protected $fillable = [
        'rekening_id',
        'jenis_keluar',
        'total_berat_keluar',
        'total_saldo_dihasilkan',
        'status',
        'tanggal_keluar',
        'user_id',
        'description',
        'berat_keluar',
    ];  

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function details()
    {
        return $this->morphMany(SampahTransactions::class, 'transactable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        // All logic is moved to SampahKeluarObserver
    }
}