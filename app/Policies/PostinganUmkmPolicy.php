<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PostinganUmkm;
use App\Models\User;

class PostinganUmkmPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PostinganUmkm');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('view PostinganUmkm');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PostinganUmkm');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('update PostinganUmkm');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('delete PostinganUmkm');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PostinganUmkm');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('restore PostinganUmkm');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PostinganUmkm');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('replicate PostinganUmkm');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PostinganUmkm');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PostinganUmkm $postinganumkm): bool
    {
        return $user->checkPermissionTo('force-delete PostinganUmkm');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PostinganUmkm');
    }
}
