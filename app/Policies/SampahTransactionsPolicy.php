<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SampahTransactions;
use App\Models\User;

class SampahTransactionsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SampahTransactions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('view SampahTransactions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SampahTransactions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('update SampahTransactions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('delete SampahTransactions');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SampahTransactions');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('restore SampahTransactions');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SampahTransactions');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('replicate SampahTransactions');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SampahTransactions');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SampahTransactions $sampahtransactions): bool
    {
        return $user->checkPermissionTo('force-delete SampahTransactions');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SampahTransactions');
    }
}
