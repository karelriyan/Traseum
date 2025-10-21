<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SetorSampah extends Model
{
    use HasUlids, SoftDeletes, HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('setor_sampah')
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Setor Sampah has been {$eventName}");
    }

    protected $table = 'setor_sampah';

    protected $guarded = [];

    protected $fillable = [
        'name',
        'text',
        'rekening_id',
        'total_berat',
        'total_saldo_dihasilkan',
        'status',
        'jenis_setoran',
        'tanggal',
        'user_id',
        'description',
        'berat',
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

    /**
     * Helper function to check if this is a donation.
     * Caches the donation account ID for efficiency.
     */
    public function isDonation(): bool
    {
        static $donationRekeningId = null;

        if (is_null($donationRekeningId)) {
            $donationRekeningId = Rekening::where('no_rekening', '00000000')->value('id');
        }

        return $this->rekening_id === $donationRekeningId;
    }

    protected static function booted(): void
    {
        // All logic is moved to SetorSampahObserver
    }
}
