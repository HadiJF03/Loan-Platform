<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;

class OfferPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->role === 'root';
    }

    /**
     * Determine if the user can create an offer.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['pledgee', 'pledger']);
    }

    /**
     * Determine if the user can update the offer.
     */
    public function update(User $user, Offer $offer): bool
    {
        return $user->id === $offer->user_id || $user->role === 'root';
    }

    /**
     * Determine if the user can delete the offer.
     */
    public function delete(User $user, Offer $offer): bool
    {
        return $user->id === $offer->user_id || $user->role === 'root';
    }

    /**
     * Determine if the user can manage an offer they didn't create.
     */
    public function manage(User $user, Offer $offer): bool
    {
        return $user->id !== $offer->user_id;
    }

    /**
     * Determine if the user can propose an amendment to the offer.
     */
    public function amend(User $user, Offer $offer): bool
    {
        return $user->id !== $offer->user_id;
    }
}
