<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SaldoTransaction;
use App\Models\User;

class SaldoTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SaldoTransaction');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('view SaldoTransaction');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SaldoTransaction');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('update SaldoTransaction');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('delete SaldoTransaction');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SaldoTransaction');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('restore SaldoTransaction');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SaldoTransaction');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('replicate SaldoTransaction');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SaldoTransaction');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SaldoTransaction $saldotransaction): bool
    {
        return $user->checkPermissionTo('force-delete SaldoTransaction');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SaldoTransaction');
    }
}
