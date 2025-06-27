<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;

class OfferPolicy
{
    public function update(User $user, Offer $offer)
    {
        // Only allow editing your own offer
        return $user->id === $offer->user_id;
    }

    public function delete(User $user, Offer $offer)
    {
        return $user->id === $offer->user_id;
    }

    public function manage(User $user, Offer $offer)
    {
        return $user->id !== $offer->user_id;
    }

    public function amend(User $user, Offer $offer)
    {
        return $user->id != $offer->user_id ;
    }


    public function create(User $user)
    {
        return in_array($user->role, ['pledgee', 'pledger']);
    }
}
