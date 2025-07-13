<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Pledge;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $offers = Offer::with([
                'user', 'pledge',
                'amendments.user',
                'amendments.amendments.user'
            ])
            ->when($user->role === 'pledger', function ($query) use ($user) {
                $pledgeIds = $user->pledges()->pluck('id');
                return $query->whereIn('pledge_id', $pledgeIds);
            }, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->whereNull('parent_offer_id')
            ->latest()
            ->paginate(15);

        return view('offers.index', compact('offers'));
    }

    public function create(Pledge $pledge)
    {
        $this->authorize('create', Offer::class);
        return view('offers.create', compact('pledge'));
    }

    public function store(Request $request, Pledge $pledge)
    {
        $this->authorize('create', Offer::class);

        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:1',
            'duration'     => 'required|integer|min:1',
            'terms'        => 'nullable|string',
        ]);

        $data['user_id']     = Auth::id();
        $data['pledge_id']   = $pledge->id;
        $data['status']      = 'pending';
        $data['is_amendment'] = false;

        Offer::create($data);

        $pledge->update(['status' => 'negotiating']);

        return redirect()->route('pledges.browse')->with('success', 'Offer submitted.');
    }

    public function accept(Offer $offer)
    {
        $this->authorize('manage', $offer);

        $offer->update(['status' => 'accepted']);

        $pledge = $offer->pledge;
        $pledge->update(['status' => 'finalized']);

        $startDate = now();
        $endDate   = now()->addDays($offer->duration);

        Transaction::create([
            'pledge_id'                         => $pledge->id,
            'offer_id'                          => $offer->id,
            'start_date'                        => $startDate,
            'end_date'                          => $endDate,
            'collateral_status'                => 'active',
            'payment_status'                   => 'pending',
            'commission'                       => 0, // Placeholder: update with real logic
            'payment_method'                   => null,
            'delivery_method'                  => null,
            'collateral_confirmed_by_pledger'  => false,
            'collateral_confirmed_by_pledgee'  => false,
            'payment_confirmed_by_pledger'     => false,
            'payment_confirmed_by_pledgee'     => false,
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Offer accepted and transaction created. Pledge marked as finalized.');
    }

    public function reject(Offer $offer)
    {
        $this->authorize('manage', $offer);

        $offer->update(['status' => 'rejected']);

        return back()->with('success', 'Offer rejected.');
    }

    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);

        $offer->delete();

        return back()->with('success', 'Offer deleted.');
    }

    public function amendForm(Offer $offer)
    {
        $this->authorize('amend', $offer);

        return view('offers.amend', compact('offer'));
    }

    public function amend(Request $request, Offer $offer)
    {
        $this->authorize('amend', $offer);

        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:1',
            'duration'     => 'required|integer|min:1',
            'terms'        => 'nullable|string',
        ]);

        $offer->update(['status' => 'amended']);

        Offer::create([
            'pledge_id'       => $offer->pledge_id,
            'user_id'         => Auth::id(),
            'offer_amount'    => $data['offer_amount'],
            'duration'        => $data['duration'],
            'terms'           => $data['terms'],
            'status'          => 'pending',
            'is_amendment'    => true,
            'parent_offer_id' => $offer->id,
        ]);

        return redirect()->route('offers.index')->with('success', 'Amended offer submitted.');
    }

    public function edit(Offer $offer)
    {
        $this->authorize('update', $offer);

        return view('offers.update', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $this->authorize('update', $offer);

        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:1',
            'duration'     => 'required|integer|min:1',
            'terms'        => 'nullable|string',
        ]);

        $offer->update($data);

        return redirect()->route('offers.index')->with('success', 'Offer updated.');
    }
}
