<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SampahSummary;
use App\Models\User;

class SampahSummaryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SampahSummary');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('view SampahSummary');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SampahSummary');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('update SampahSummary');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('delete SampahSummary');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SampahSummary');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('restore SampahSummary');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SampahSummary');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('replicate SampahSummary');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SampahSummary');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SampahSummary $sampahsummary): bool
    {
        return $user->checkPermissionTo('force-delete SampahSummary');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SampahSummary');
    }
}
