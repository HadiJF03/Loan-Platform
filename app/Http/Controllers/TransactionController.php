<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Pledge;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Eager load pledge and offer (2-level parent nesting)
        $allTransactions = Transaction::with([
            'pledge',
            'offer.parentOffer.parentOffer'
        ])->get();

        // Filter transactions for current user
        $filtered = $allTransactions->filter(function ($transaction) use ($user) {
            $pledgerId = optional($transaction->pledge)->user_id;

            // Traverse to the root offer (original pledgee)
            $rootOffer = $transaction->offer;
            while ($rootOffer?->parentOffer) {
                $rootOffer = $rootOffer->parentOffer;
            }
            $pledgeeId = $rootOffer?->user_id;

            return $user->id === $pledgerId || $user->id === $pledgeeId;
        });

        // Manual pagination
        $perPage = 10;
        $page = request('page', 1);
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('transactions.index', ['transactions' => $paginated]);
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'payment_method'  => 'required|in:Card Payment,Bank Transfer,STC Pay',
            'delivery_method' => 'required|in:in-person,shipping,secure drop point',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction updated with delivery and payment method.');
    }

    public function store(Request $request, Pledge $pledge, Offer $offer)
    {
        $this->authorize('create', [Transaction::class, $pledge, $offer]);

        if ($pledge->transaction) {
            return back()->with('error', 'Transaction already exists.');
        }

        $validated = $request->validate([
            'payment_method'  => 'required|in:Card Payment,Bank Transfer,STC Pay',
            'delivery_method' => 'required|in:in-person,shipping,secure drop point',
        ]);

        $startDate  = now();
        $endDate    = $startDate->copy()->addDays($offer->duration);
        $commission = $offer->offer_amount * 0.05;

        $transaction = Transaction::create([
            'pledge_id'         => $pledge->id,
            'offer_id'          => $offer->id,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'collateral_status' => 'active',
            'payment_status'    => 'pending',
            'commission'        => $commission,
            'payment_method'    => $validated['payment_method'],
            'delivery_method'   => $validated['delivery_method'],
            'collateral_confirmed_by_pledger' => false,
            'collateral_confirmed_by_pledgee' => false,
            'payment_confirmed_by_pledger'    => false,
            'payment_confirmed_by_pledgee'    => false,
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction created successfully.');
    }

    public function complete(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update([
            'collateral_status' => 'closed',
            'payment_status'    => 'paid',
        ]);

        return back()->with('success', 'Transaction marked as completed.');
    }

    public function confirmCollateral(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $user = Auth::user();

        if ($user->id === $transaction->pledge->user_id) {
            $transaction->update(['collateral_confirmed_by_pledger' => true]);
        } else {
            $rootOffer = $transaction->offer;
            while ($rootOffer->parentOffer) {
                $rootOffer = $rootOffer->parentOffer;
            }

            if ($user->id === $rootOffer->user_id) {
                $transaction->update(['collateral_confirmed_by_pledgee' => true]);
            }
        }

        $this->checkAndComplete($transaction);

        return back()->with('success', 'Collateral confirmation saved.');
    }

    public function confirmPayment(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $user = Auth::user();

        if ($user->id === $transaction->pledge->user_id) {
            $transaction->update(['payment_confirmed_by_pledger' => true]);
        } else {
            $rootOffer = $transaction->offer;
            while ($rootOffer->parentOffer) {
                $rootOffer = $rootOffer->parentOffer;
            }

            if ($user->id === $rootOffer->user_id) {
                $transaction->update(['payment_confirmed_by_pledgee' => true]);
            }
        }

        $this->checkAndComplete($transaction);

        return back()->with('success', 'Payment confirmation saved.');
    }

    /**
     * Automatically mark transaction as completed if all confirmations are true
     */
    protected function checkAndComplete(Transaction $transaction)
    {
        if (
            $transaction->collateral_confirmed_by_pledger &&
            $transaction->collateral_confirmed_by_pledgee &&
            $transaction->payment_confirmed_by_pledger &&
            $transaction->payment_confirmed_by_pledgee
        ) {
            $transaction->update([
                'collateral_status' => 'closed',
                'payment_status'    => 'paid',
            ]);
        }
    }
}
