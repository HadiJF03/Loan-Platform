<?php

namespace App\Http\Controllers;

use App\Models\Pledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    public function index()
    {
        $pledges = Pledge::with('user')->latest()->get();
        return view('pledges.index', compact('pledges'));
    }

    public function browse(Request $request)
    {
        $this->authorize('browse', Pledge::class);

        $query = Pledge::with('user');

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('min_amount')) {
            $query->where('requested_amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('requested_amount', '<=', $request->max_amount);
        }

        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'high_amount':
                $query->orderByDesc('requested_amount');
                break;
            case 'low_amount':
                $query->orderBy('requested_amount');
                break;
            default:
                $query->latest();
                break;
        }

        $pledges = $query->paginate(10)->withQueryString();

        return view('pledges.browse', compact('pledges'));
    }

    public function create()
    {
        $this->authorize('create', Pledge::class);

        return view('pledges.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pledge::class);

        $data = $request->validate([
            'item_type'           => 'required|string|in:Jewelry,Electronics,Vehicles,Real Estate,Precious Metals',
            'description'         => 'nullable|string',
            'requested_amount'    => 'required|numeric',
            'collateral_duration' => 'required|integer',
            'repayment_terms'     => 'nullable|string',
            'images.*'            => 'image|max:2048',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('pledge_images', 'public');
            }
            $data['images'] = json_encode($paths);
        }

        Pledge::create($data);

        return redirect()->route('pledges.index')->with('success', 'Pledge submitted.');
    }

    public function show(Pledge $pledge)
    {
        $this->authorize('view', $pledge);

        return view('pledges.show', compact('pledge'));
    }

    public function edit(Pledge $pledge)
    {
        $this->authorize('update', $pledge);

        return view('pledges.edit', compact('pledge'));
    }

    public function update(Request $request, Pledge $pledge)
    {
        $this->authorize('update', $pledge);

        $data = $request->validate([
            'description'         => 'required|string',
            'requested_amount'    => 'required|numeric|min:0',
            'collateral_duration' => 'required|integer',
            'repayment_terms'     => 'nullable|string',
            'images.*'            => 'image|max:2048',
        ]);

        // Handle optional image replacement
        if ($request->hasFile('images')) {
            // Optionally delete old images
            if ($pledge->images) {
                foreach (json_decode($pledge->images, true) as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('pledge_images', 'public');
            }
            $data['images'] = json_encode($paths);
        }

        $pledge->update($data);

        return redirect()->route('pledges.index')->with('success', 'Pledge updated successfully.');
    }

    public function destroy(Pledge $pledge)
    {
        $this->authorize('delete', $pledge);

        // Optional: delete images on removal
        if ($pledge->images) {
            foreach (json_decode($pledge->images, true) as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $pledge->delete();

        return redirect()->route('pledges.index')->with('success', 'Pledge deleted.');
    }
}
