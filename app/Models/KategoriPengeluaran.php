<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class KategoriPengeluaran extends Model
{
    use HasUlids, HasFactory;
    protected $table = 'kategori_pengeluaran';
    protected $guarded = ['id'];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'nama_pengeluaran');
    }
}
