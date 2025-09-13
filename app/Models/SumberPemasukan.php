<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class SumberPemasukan extends Model
{
    use HasUlids, HasFactory;
    protected $table = 'sumber_pemasukan';
    protected $guarded = ['id'];

    public function pemasukan()
    {
        return $this->belongsTo(Pemasukan::class, 'nama_pemasukan');
    }

}
