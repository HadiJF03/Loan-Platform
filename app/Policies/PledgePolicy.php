<?php

namespace App\Policies;

use App\Models\Pledge;
use App\Models\User;

class PledgePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'root';
    }

    /**
     * Determine whether the user can browse pledges.
     */
    public function browse(User $user): bool
    {
        return $user->role === 'pledgee' || $user->role === 'root';
    }

    /**
     * Determine whether the user can view a specific pledge.
     */
    public function view(User $user, Pledge $pledge): bool
    {
        return $user->id === $pledge->user_id || $user->role === 'root';
    }

    /**
     * Determine whether the user can create a new pledge.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pledger';
    }

    /**
     * Determine whether the user can update an existing pledge.
     */
    public function update(User $user, Pledge $pledge): bool
    {
        return $user->id === $pledge->user_id || $user->role === 'root';
    }

    /**
     * Determine whether the user can delete a pledge.
     */
    public function delete(User $user, Pledge $pledge): bool
    {
        return $user->id === $pledge->user_id || $user->role === 'root';
    }

    /**
     * Determine whether the user can restore a deleted pledge.
     */
    public function restore(User $user, Pledge $pledge): bool
    {
        return $user->role === 'root';
    }

    /**
     * Determine whether the user can permanently delete a pledge.
     */
    public function forceDelete(User $user, Pledge $pledge): bool
    {
        return false;
    }
}
