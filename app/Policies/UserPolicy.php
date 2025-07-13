<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Allow only root users to view the user list.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'root';
    }

    /**
     * Allow only root users to view individual users.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === 'root';
    }

    /**
     * Allow only root users to create new users.
     */
    public function create(User $user): bool
    {
        return $user->role === 'root';
    }

    /**
     * Allow only root users to update any user.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role === 'root';
    }

    /**
     * Allow root users to delete other users, but not themselves.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'root' && $user->id !== $model->id;
    }

    /**
     * Allow only root users to restore users.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === 'root';
    }

    /**
     * Allow root users to permanently delete other users, but not themselves.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'root' && $user->id !== $model->id;
    }
}
