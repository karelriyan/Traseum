<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any news.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can view the news.
     */
    public function view(User $user, News $news): bool
    {
        return $user->can('view_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can create news.
     */
    public function create(User $user): bool
    {
        return $user->can('create_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can update the news.
     */
    public function update(User $user, News $news): bool
    {
        return $user->can('edit_news') || $user->hasRole('Super Admin') || $news->author_id === $user->id;
    }

    /**
     * Determine whether the user can delete the news.
     */
    public function delete(User $user, News $news): bool
    {
        return $user->can('delete_news') || $user->hasRole('Super Admin') || $news->author_id === $user->id;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, News $news): bool
    {
        return $user->can('force_delete_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, News $news): bool
    {
        return $user->can('restore_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, News $news): bool
    {
        return $user->can('replicate_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_news') || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can publish/unpublish.
     */
    public function publish(User $user, News $news): bool
    {
        return $user->can('publish_news') || $user->hasRole('Super Admin');
    }
}
