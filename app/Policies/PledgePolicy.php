<?php

namespace App\Policies;

use App\Models\Pledge;
use App\Models\User;
class PledgePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function browse(User $user): bool
    {
        return $user->role === 'pledgee';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pledge $pledge): bool
    {
        return $user->id === $pledge->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pledger';    
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pledge $pledge)
    {
        return $user->id === $pledge->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pledge $pledge)
    {
        return $user->id === $pledge->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pledge $pledge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pledge $pledge): bool
    {
        return false;
    }
}
