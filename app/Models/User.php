<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HasRoles, HasSuperAdmin;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'custom_fields',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'custom_fields' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access to admin panel for users with specific roles
        return $this->hasRole(['Super Admin', 'admin']) || $this->isSuperAdmin();
    }

    public function role(): string
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the full URL for the avatar image.
     */
    public function getAvatarUrlFullAttribute(): ?string
    {
        $avatarPath = $this->attributes['avatar_url'] ?? null;
        
        if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
            return Storage::disk('public')->url($avatarPath);
        }
        
        return null;
    }

    /**
     * Get avatar for display purposes.
     */
    public function getAvatarDisplayAttribute(): ?string
    {
        return $this->attributes['avatar_url'] ?? null;
    }

    /**
     * Get the avatar URL for Filament (override default behavior).
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarPath = $this->attributes['avatar_url'] ?? null;
        
        if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
            return asset('storage/' . $avatarPath);
        }
        
        return null;
    }

    /**
     * Get the avatar URL attribute for general use.
     */
    public function getAvatarUrlAttribute()
    {
        // Return the raw path from database
        return $this->attributes['avatar_url'] ?? null;
    }

    // protected static function booted(): void
    // {
    //     Gate::before(function (User $user, string $ability) {
    //         return $user->isSuperAdmin() ? true : null;
    //     });
    // }
}
