<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pledges
    Route::resource('pledges', PledgeController::class);
    Route::get('/browse-pledges', [PledgeController::class, 'browse'])->name('pledges.browse');

    // Offers
    Route::get('/pledges/{pledge}/offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/pledges/{pledge}/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');

    // Offer Actions
    Route::post('/offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
    Route::post('/offers/{offer}/reject', [OfferController::class, 'reject'])->name('offers.reject');
    Route::get('/offers/{offer}/amend', [OfferController::class, 'amendForm'])->name('offers.amend.form');
    Route::post('/offers/{offer}/amend', [OfferController::class, 'amend'])->name('offers.amend');

    // Transactions
    Route::post('/pledges/{pledge}/offers/{offer}/transaction', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::post('/transactions/{transaction}/confirm-collateral', [TransactionController::class, 'confirmCollateral'])->name('transactions.confirmCollateral');
    Route::post('/transactions/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('transactions.confirmPayment');
    Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');

    // Admin Panel
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // 1. Pledges Management
        Route::get('/pledges', [AdminController::class, 'pledges'])->name('pledges.index');
        Route::delete('/pledges/{pledge}', [AdminController::class, 'deletePledge'])->name('pledges.delete');

        // 2. Offers Management
        Route::get('/offers', [AdminController::class, 'offers'])->name('offers.index');
        Route::delete('/offers/{offer}', [AdminController::class, 'deleteOffer'])->name('offers.delete');

        // 3. Transactions Management
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions.index');
        Route::delete('/transactions/{transaction}', [AdminController::class, 'deleteTransaction'])->name('transactions.delete');

        // 4. Users Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // 5. System Overview
        Route::get('/overview', [AdminController::class, 'overview'])->name('overview');

        // 6. Category Management (Fixed Route Naming)
        Route::resource('categories', CategoryController::class);
    });
});
