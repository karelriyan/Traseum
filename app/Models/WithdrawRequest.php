<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WithdrawRequest extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'withdraw_requests';

    protected $fillable = [
        'rekening_id',
        'user_id',
        'amount',
        'jenis',
        'catatan',
        'bank_name',
        'account_number',
        'account_holder_name',
        'status',
        'notes',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function saldoTransaction()
    {
        return $this->hasOne(SaldoTransaction::class, 'transactable_id')
            ->where('transactable_type', WithdrawRequest::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($withdrawRequest) {
            if (!$withdrawRequest->user_id && Auth::check()) {
                $withdrawRequest->user_id = Auth::id();
            }
        });
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'processed' => 'info',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'processed' => 'Diproses',
            default => $this->status,
        };
    }
}
