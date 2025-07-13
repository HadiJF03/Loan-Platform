<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pledge;
use App\Models\Offer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
class AdminController extends Controller
{
    public function dashboard()
    {
        $this->authorize('viewAny', User::class);
        return view('admin.dashboard');
    }

    public function pledges(Request $request)
    {
        $this->authorize('viewAny', Pledge::class);

        $query = Pledge::with(['user', 'offers', 'category']);

        // ✅ Filter by category_id instead of item_type
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_amount')) {
            $query->where('requested_amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('requested_amount', '<=', $request->max_amount);
        }

        $pledges = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $categories = Category::all(); // ✅ load all categories for filter dropdown in the view

        return view('admin.pledges', compact('pledges', 'categories'));
    }
    public function deletePledge(Pledge $pledge)
    {
        $this->authorize('delete', $pledge);
        $pledge->delete();

        return back()->with('success', 'Pledge deleted successfully.');
    }

    public function offers()
    {
        $this->authorize('viewAny', Offer::class);

        $offers = Offer::with(['pledge', 'user'])->latest()->paginate(15);

        return view('admin.offers', compact('offers'));
    }

    public function deleteOffer(Offer $offer)
    {
        $this->authorize('delete', $offer);
        $offer->delete();

        return back()->with('success', 'Offer deleted successfully.');
    }

    public function transactions()
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = Transaction::with(['pledge.user', 'offer.user'])->latest()->paginate(15);

        return view('admin.transactions', compact('transactions'));
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return back()->with('success', 'Transaction deleted successfully.');
    }

    public function users()
    {
        $this->authorize('viewAny', User::class);

        $users = User::latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
