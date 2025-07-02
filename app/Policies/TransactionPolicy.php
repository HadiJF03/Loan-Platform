<?php
namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction): bool
    {
        $pledgerId = optional($transaction->pledge)->user_id;
        $pledgeeId = optional($transaction->offer?->rootOffer())->user_id;

        return $user->id === $pledgerId || $user->id === $pledgeeId;
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return false;
    }

    public function confirmCollateral(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    public function confirmPayment(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }
    public function confirmCollateralSent(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id;
    }

    public function confirmCollateralReceived(User $user, Transaction $transaction): bool
    {
        $rootOffer = $transaction->offer;
        while ($rootOffer->parentOffer) {
            $rootOffer = $rootOffer->parentOffer;
        }

        return $user->id === $rootOffer->user_id && $transaction->collateral_confirmed_by_pledger;
    }

    public function confirmPaymentSent(User $user, Transaction $transaction): bool
    {
        $rootOffer = $transaction->offer;
        while ($rootOffer->parentOffer) {
            $rootOffer = $rootOffer->parentOffer;
        }

        return $user->id === $rootOffer->user_id;
    }

    public function confirmPaymentReceived(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id && $transaction->payment_confirmed_by_pledgee;
    }

}
