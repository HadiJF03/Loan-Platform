<?php

namespace App\Http\Controllers;

use App\Models\Pledge;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
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
        }

        $pledges = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('pledges.browse', compact('pledges', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Pledge::class);

        $categories = Category::all();
        return view('pledges.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pledge::class);

        $data = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'description'         => 'nullable|string',
            'requested_amount'    => 'required|numeric|min:0',
            'collateral_duration' => 'required|integer|min:1',
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

        $categories = Category::all();
        return view('pledges.edit', compact('pledge', 'categories'));
    }

    public function update(Request $request, Pledge $pledge)
    {
        $this->authorize('update', $pledge);

        $data = $request->validate([
            'category_id'         => 'required|exists:categories,id',
            'description'         => 'required|string',
            'requested_amount'    => 'required|numeric|min:0',
            'collateral_duration' => 'required|integer|min:1',
            'repayment_terms'     => 'nullable|string',
            'images.*'            => 'image|max:2048',
        ]);

        if ($request->hasFile('images')) {
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

        if ($pledge->images) {
            foreach (json_decode($pledge->images, true) as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $pledge->delete();

        return redirect()->route('pledges.index')->with('success', 'Pledge deleted.');
    }
}
