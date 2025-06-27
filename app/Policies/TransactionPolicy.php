<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine if the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id
            || $user->id === $transaction->offer->user_id;
    }

    /**
     * Determine if the user can update the transaction.
     * (used for confirming collateral or payment)
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id
            || $user->id === $transaction->offer->user_id;
    }

    /**
     * Optional: Determine if the user can delete the transaction.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return false; // You can customize this if needed
    }

    /**
     * Determine if the user can confirm collateral.
     */
    public function confirmCollateral(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id
            || $user->id === $transaction->offer->user_id;
    }

    /**
     * Determine if the user can confirm payment.
     */
    public function confirmPayment(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->pledge->user_id
            || $user->id === $transaction->offer->user_id;
    }
}
