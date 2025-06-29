<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OtpController;


Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('pledges', PledgeController::class);
    Route::get('/browse-pledges', [PledgeController::class, 'browse'])->name('pledges.browse');

    Route::get('/pledges/{pledge}/offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/pledges/{pledge}/offers', [OfferController::class, 'store'])->name('offers.store');

    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');

    Route::post('/offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
    Route::post('/offers/{offer}/reject', [OfferController::class, 'reject'])->name('offers.reject');
    Route::get('/offers/{offer}/amend', [OfferController::class, 'amendForm'])->name('offers.amend.form');
    Route::post('/offers/{offer}/amend', [OfferController::class, 'amend'])->name('offers.amend');
    Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
    Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');




    Route::post('/pledges/{pledge}/offers/{offer}/transaction', [TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::post('/transactions/{transaction}/confirm-collateral', [TransactionController::class, 'confirmCollateral'])->name('transactions.confirmCollateral');
    Route::post('/transactions/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('transactions.confirmPayment');
    Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');

    

});


