<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can view the role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('view_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->can('create_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can('edit_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('force_delete_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->can('restore_role') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_role') || $user->hasRole('Super Admin');
    }

    /**
     * Replicate.
     */
    public function replicate(User $user, Role $role): bool
    {
        return $user->can('replicate_role') || $user->hasRole('Super Admin');
    }

    /**
     * Reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_role') || $user->hasRole('Super Admin');
    }
}
