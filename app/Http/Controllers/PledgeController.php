<?php

namespace App\Http\Controllers;

use App\Models\Pledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pledges = Pledge::with('user')->latest()->get();
        return view('pledges.index', compact('pledges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pledges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'item_type' => 'required|string|max:255',
        'description' => 'nullable|string',
        'requested_amount' => 'required|numeric',
        'collateral_duration' => 'required|integer',
        'repayment_terms' => 'nullable|string',
    ]);
        $data['user_id'] = Auth::id();
        Pledge::create($data);

        return redirect()->route('pledges.index')->with('success', 'Pledge submitted.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pledge $pledge)
    {
        return view('pledges.show', compact('pledge'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pledge $pledge)
    {
        if ($pledge->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pledges.edit', compact('pledge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pledge $pledge)
    {
        if ($pledge->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'item_type'           => 'required|string|max:255',
            'description'         => 'nullable|string',
            'requested_amount'    => 'required|numeric',
            'collateral_duration' => 'required|integer',
            'repayment_terms'     => 'nullable|string',
            'status'              => 'required|in:open,negotiating,finalized,withdrawn',
        ]);

        $pledge->update($data);

        return redirect()
            ->route('pledges.show', $pledge)
            ->with('success', 'Pledge updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pledge $pledge)
    {
        if ($pledge->user_id !== Auth::id()) {
            abort(403);
        }

        $pledge->delete();

        return redirect()
            ->route('pledges.index')
            ->with('success', 'Pledge deleted.');
    }
}
