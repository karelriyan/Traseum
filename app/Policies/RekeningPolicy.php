<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Rekening;
use App\Models\User;

class RekeningPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Rekening');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('view Rekening');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Rekening');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('update Rekening');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('delete Rekening');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Rekening');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('restore Rekening');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Rekening');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('replicate Rekening');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Rekening');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rekening $rekening): bool
    {
        return $user->checkPermissionTo('force-delete Rekening');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Rekening');
    }
}
