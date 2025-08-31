<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\WithdrawRequest;
use App\Models\User;

class WithdrawRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WithdrawRequest');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('view WithdrawRequest');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WithdrawRequest');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('update WithdrawRequest');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('delete WithdrawRequest');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any WithdrawRequest');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('restore WithdrawRequest');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any WithdrawRequest');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('replicate WithdrawRequest');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder WithdrawRequest');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WithdrawRequest $withdrawrequest): bool
    {
        return $user->checkPermissionTo('force-delete WithdrawRequest');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any WithdrawRequest');
    }
}
