<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Sampah;
use App\Models\User;

class SampahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Sampah');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('view Sampah');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Sampah');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('update Sampah');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('delete Sampah');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Sampah');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('restore Sampah');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Sampah');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('replicate Sampah');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Sampah');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sampah $sampah): bool
    {
        return $user->checkPermissionTo('force-delete Sampah');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Sampah');
    }
}
