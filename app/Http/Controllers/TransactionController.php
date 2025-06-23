<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Pledge;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $transactions = Transaction::with(['pledge', 'offer'])
            ->where('lender_id', $userId)
            ->orWhere('borrower_id', $userId)
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.show', compact('transaction'));
    }


    public function store(Pledge $pledge, Offer $offer)
    {
        if ($pledge->transaction) {
            return back()->with('error', 'Transaction already exists.');
        }

        $transaction = Transaction::create([
            'pledge_id'    => $pledge->id,
            'offer_id'     => $offer->id,
            'borrower_id'  => $pledge->user_id,
            'lender_id'    => $offer->user_id,
            'amount'       => $offer->offer_amount,
            'duration'     => $offer->duration,
            'status'       => 'active',
            'started_at'   => now(),
            'due_at'       => now()->addDays($offer->duration),
        ]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaction created successfully.');
    }

    public function complete(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Transaction marked as completed.');
    }
}
