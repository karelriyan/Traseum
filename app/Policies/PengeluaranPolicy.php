<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Pengeluaran;
use App\Models\User;

class PengeluaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pengeluaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('view Pengeluaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pengeluaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('update Pengeluaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('delete Pengeluaran');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pengeluaran');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('restore Pengeluaran');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pengeluaran');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('replicate Pengeluaran');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pengeluaran');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pengeluaran $pengeluaran): bool
    {
        return $user->checkPermissionTo('force-delete Pengeluaran');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pengeluaran');
    }
}
