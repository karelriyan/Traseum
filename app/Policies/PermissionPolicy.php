<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissions.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can view the permission.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can create permissions.
     */
    public function create(User $user): bool
    {
        return $user->can('create_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can update the permission.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->can('edit_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can delete the permission.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('force_delete_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('restore_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Permission $permission): bool
    {
        return $user->can('replicate_permission') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_permission') || $user->hasRole('Super Admin');
    }
}
