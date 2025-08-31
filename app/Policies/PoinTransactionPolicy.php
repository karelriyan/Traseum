<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PoinTransaction;
use App\Models\User;

class PoinTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PoinTransaction');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('view PoinTransaction');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PoinTransaction');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('update PoinTransaction');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('delete PoinTransaction');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PoinTransaction');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('restore PoinTransaction');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PoinTransaction');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('replicate PoinTransaction');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PoinTransaction');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PoinTransaction $pointransaction): bool
    {
        return $user->checkPermissionTo('force-delete PoinTransaction');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PoinTransaction');
    }
}
