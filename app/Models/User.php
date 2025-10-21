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
use Hexters\HexaLite\HexaLiteRolePermission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HexaLiteRolePermission;

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

    /**
     * Boot the model and add event listeners for cache management.
     */
    protected static function booted(): void
    {
        // Clear cache when user is updated
        static::updated(function (User $user) {
            $user->clearUserCache();
        });

        // Clear cache when user is deleted
        static::deleted(function (User $user) {
            $user->clearUserCache();
        });

        static::deleting(function ($user) {
            if ($user->roles()->where('name', 'Super Admin')->exists()) {
                throw new \Exception('Super Admin tidak dapat dihapus.');
            }
        });

        // Clear cache when user roles are updated
        static::saved(function (User $user) {
            if ($user->wasRecentlyCreated || $user->isDirty(['name', 'email', 'avatar_url'])) {
                $user->clearUserCache();
            }
        });
    }

    /**
     * Clear all cache entries for this user.
     */
    public function clearUserCache(): void
    {
        $cacheKeys = [
            "user_avatar_{$this->id}",
            "user_avatar_full_{$this->id}",
            "user_roles_{$this->id}",
            "user_permissions_{$this->id}",
            "user_super_admin_{$this->id}",
            "user_can_access_panel_{$this->id}",
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    public function isSuperAdmin(): bool
    {
        return Cache::remember("user_super_admin_{$this->id}", 3600, function () {
            return $this->hasRole('Super Admin');
        });
    }

    /**
     * Determine if the user can access the given Filament panel (with caching).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return Cache::remember("user_can_access_panel_{$this->id}", 3600, function () {
            // Allow access to admin panel for users with specific roles
            return $this->hasRole(['Super Admin', 'Admin']) || $this->isSuperAdmin();
        });
    }

    /**
     * Get user roles with caching.
     */
    public function getCachedRoles(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("user_roles_{$this->id}", 3600, function () {
            return $this->roles()->get();
        });
    }

    /**
     * Get user permissions with caching.
     */
    public function getCachedPermissions(): array
    {
        return Cache::remember("user_permissions_{$this->id}", 3600, function () {
            $gates = [];
            // Aggregate gates from roles' access arrays
            $roles = $this->roles()->get();
            foreach ($roles as $role) {
                if (is_array($role->access)) {
                    foreach ($role->access as $access) {
                        $gates[$access] = true; // use associative array for uniqueness
                    }
                }
            }
            return array_keys($gates);
        });
    }

    // Hapus method role() yang salah - sudah ada trait HasRoles dari Spatie

    /**
     * Get the full URL for the avatar image.
     */
    public function getAvatarUrlFullAttribute(): ?string
    {
        $avatarPath = $this->attributes['avatar_url'] ?? null;

        if (!$avatarPath) {
            return null;
        }

        // If already a full URL, return as is
        if (filter_var($avatarPath, FILTER_VALIDATE_URL)) {
            return $avatarPath;
        }

        // If it's a relative path and the file exists on the public disk, build the asset URL
        if (Storage::disk('public')->exists($avatarPath)) {
            return asset('storage/' . ltrim($avatarPath, '/'));
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
     * Get the avatar URL for Filament (override default behavior) with caching.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return Cache::remember("user_avatar_{$this->id}", 3600, function () {
            $avatarPath = $this->attributes['avatar_url'] ?? null;

            if (!$avatarPath) {
                // Return a consistent placeholder instead of null to prevent flickering
                return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=10b981&color=ffffff';
            }

            // If it's already a full URL, return as is
            if (filter_var($avatarPath, FILTER_VALIDATE_URL)) {
                return $avatarPath;
            }

            // If it's a relative path, construct full URL
            return asset('storage/' . $avatarPath);
        });
    }

    /**
     * Get the avatar URL attribute for general use (cached).
     */
    public function getAvatarAttribute()
    {
        return $this->getFilamentAvatarUrl();
    }

    /**
     * Get the raw avatar_url attribute.
     */
    public function getAvatarUrlAttribute()
    {
        // Return the raw path from database
        return $this->attributes['avatar_url'] ?? null;
    }

    /**
     * Check if user has a specific role (Hexa Lite compatible).
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }

        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has a specific role (with caching).
     */
    public function hasRoleCached(string $role): bool
    {
        $roles = $this->getCachedRoles();
        return $roles->contains('name', $role);
    }

    /**
     * Check if user has any of the given roles (with caching).
     */
    public function hasAnyRoleCached(array $roles): bool
    {
        $userRoles = $this->getCachedRoles();
        return $userRoles->whereIn('name', $roles)->isNotEmpty();
    }

    /**
     * Check if user has a specific permission (with caching).
     */
    public function hasPermissionCached(string $permission): bool
    {
        $permissions = $this->getCachedPermissions();
        return in_array($permission, $permissions, true);
    }

    /**
     * Refresh user cache manually.
     */
    public function refreshCache(): void
    {
        $this->clearUserCache();

        // Preload commonly used cache entries
        $this->getFilamentAvatarUrl();
        $this->getCachedRoles();
        $this->getCachedPermissions();
        $this->isSuperAdmin();
    }

    /**
     * Get cache key for a specific user attribute.
     */
    public function getCacheKey(string $attribute): string
    {
        return "user_{$attribute}_{$this->id}";
    }

    /**
     * Compatibility method: check permission using Hexa Lite gates.
     * Mirrors Spatie's checkPermissionTo but delegates to hexa()->can().
     */
    public function checkPermissionTo(array|string $permissions): bool
    {
        return hexa()->user($this)->can($permissions);
    }
}
