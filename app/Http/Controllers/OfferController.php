<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Pledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    $offers = Offer::with('pledge')->where('user_id', $userId) ->latest()->paginate(15);
    return view('offers.index', compact('offers'));
}
    public function create(Pledge $pledge)
    {
        return view('offers.create', compact('pledge'));
    }


    public function store(Request $request, Pledge $pledge)
    {
        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:1',
            'duration'     => 'required|integer|min:1',
            'terms'        => 'nullable|string',
        ]);

        $data['user_id']     = Auth::id();
        $data['pledge_id']   = $pledge->id;
        $data['status']      = 'pending';     // default
        $data['is_amendment'] = false;

        Offer::create($data);

        return redirect()
            ->route('pledges.show', $pledge)
            ->with('success', 'Offer submitted.');
    }

    public function accept(Offer $offer)
    {
        $this->authorize('update', $offer->pledge);

        $offer->update(['status' => 'accepted']);

        return back()->with('success', 'Offer accepted.');
    }

    public function reject(Offer $offer)
    {
        $this->authorize('update', $offer->pledge);

        $offer->update(['status' => 'rejected']);

        return back()->with('success', 'Offer rejected.');
    }

    public function amendForm(Offer $offer)
    {
        return view('offers.amend', compact('offer'));
    }

    public function amend(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:1',
            'duration'     => 'required|integer|min:1',
            'terms'        => 'nullable|string',
        ]);

        $newOffer = Offer::create([
            'pledge_id'       => $offer->pledge_id,
            'user_id'         => Auth::id(),
            'offer_amount'    => $data['offer_amount'],
            'duration'        => $data['duration'],
            'terms'           => $data['terms'],
            'status'          => 'pending',
            'is_amendment'    => true,
            'parent_offer_id' => $offer->id,
        ]);

        return redirect()
            ->route('pledges.show', $offer->pledge)
            ->with('success', 'Amended offer submitted.');
    }
}
