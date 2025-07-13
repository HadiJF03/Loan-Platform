<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'root';
    }

    /**
     * Determine whether the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        $pledgerId = optional($transaction->pledge)->user_id;
        $pledgeeId = optional($transaction->offer?->rootOffer())->user_id;

        return $user->id === $pledgerId || $user->id === $pledgeeId || $user->role === 'root';
    }

    /**
     * Allow update if the user can view the transaction.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    /**
     * Only root users can delete transactions.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->role === 'root';
    }

    /**
     * Both parties involved can confirm collateral.
     */
    public function confirmCollateral(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    /**
     * Both parties involved can confirm payment.
     */
    public function confirmPayment(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    /**
     * Only the pledger can confirm that collateral was sent.
     */
    public function confirmCollateralSent(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id;
    }

    /**
     * Only the pledgee (root offer owner) can confirm collateral receipt.
     */
    public function confirmCollateralReceived(User $user, Transaction $transaction): bool
    {
        $rootOffer = $transaction->offer;
        while ($rootOffer->parentOffer) {
            $rootOffer = $rootOffer->parentOffer;
        }

        return $user->id === $rootOffer->user_id && $transaction->collateral_confirmed_by_pledger;
    }

    /**
     * Only the pledgee (root offer owner) can confirm payment sent.
     */
    public function confirmPaymentSent(User $user, Transaction $transaction): bool
    {
        $rootOffer = $transaction->offer;
        while ($rootOffer->parentOffer) {
            $rootOffer = $rootOffer->parentOffer;
        }

        return $user->id === $rootOffer->user_id;
    }

    /**
     * Only the pledger can confirm payment was received.
     */
    public function confirmPaymentReceived(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id && $transaction->payment_confirmed_by_pledgee;
    }
}
