<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SumberPemasukan;
use App\Models\User;

class SumberPemasukanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SumberPemasukan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('view SumberPemasukan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SumberPemasukan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('update SumberPemasukan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('delete SumberPemasukan');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SumberPemasukan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('restore SumberPemasukan');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SumberPemasukan');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('replicate SumberPemasukan');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SumberPemasukan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SumberPemasukan $sumberpemasukan): bool
    {
        return $user->checkPermissionTo('force-delete SumberPemasukan');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SumberPemasukan');
    }
}
