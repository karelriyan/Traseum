<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SetorSampah;
use App\Models\User;

class SetorSampahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SetorSampah');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('view SetorSampah');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SetorSampah');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('update SetorSampah');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('delete SetorSampah');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SetorSampah');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('restore SetorSampah');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SetorSampah');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('replicate SetorSampah');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SetorSampah');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SetorSampah $setorsampah): bool
    {
        return $user->checkPermissionTo('force-delete SetorSampah');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SetorSampah');
    }
}
