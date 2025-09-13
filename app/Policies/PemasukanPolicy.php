<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Pemasukan;
use App\Models\User;

class PemasukanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pemasukan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('view Pemasukan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pemasukan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('update Pemasukan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('delete Pemasukan');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pemasukan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('restore Pemasukan');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pemasukan');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('replicate Pemasukan');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pemasukan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pemasukan $pemasukan): bool
    {
        return $user->checkPermissionTo('force-delete Pemasukan');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pemasukan');
    }
}
