<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Concerns\HasUlids;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Support\Facades\Auth;

// class Nasabah extends Model
// {
//     use HasUlids, SoftDeletes, HasFactory;

//     protected $table = 'nasabah';
//     protected $guarded = [];

//     public function rekening()
//     {
//         return $this->belongsTo(Rekening::class);
//     }

//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }

//     protected static function booted(): void
//     {
//         static::creating(function ($Nasabah) {
//             if (!$Nasabah->user_id && Auth::check()) {
//                 $Nasabah->user_id = Auth::id();
//             }
//         });
//     }
// }
