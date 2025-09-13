<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\KategoriPengeluaran;
use App\Models\User;

class KategoriPengeluaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any KategoriPengeluaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('view KategoriPengeluaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create KategoriPengeluaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('update KategoriPengeluaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('delete KategoriPengeluaran');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any KategoriPengeluaran');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('restore KategoriPengeluaran');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any KategoriPengeluaran');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('replicate KategoriPengeluaran');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder KategoriPengeluaran');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KategoriPengeluaran $kategoripengeluaran): bool
    {
        return $user->checkPermissionTo('force-delete KategoriPengeluaran');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any KategoriPengeluaran');
    }
}
