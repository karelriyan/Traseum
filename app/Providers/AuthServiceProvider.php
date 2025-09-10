<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\News;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\NewsPolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        News::class => NewsPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(News::class, NewsPolicy::class);

        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}
