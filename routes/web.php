<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;

Route::get('/scratch', function () {
});

Route::get('/', [DashboardController::class, 'show'])
     ->name('dashboard')
     ->middleware(['auth', 'verified']);

/**
 * Invitation handling
 */
Route::prefix('invitation')->group(function () {
    Route::get('/create', [InvitationController::class, 'create'])->name('invitation.create')
            ->middleware(['auth', 'auth.admin']);
    Route::post('/', [InvitationController::class, 'store'])->name('invitation.store')
            ->middleware(['auth', 'auth.admin']);
    Route::get('/{invitation}', [InvitationController::class, 'confirm'])->name('invitation.confirm')
            ->middleware('guest');
    Route::post('/{invitation}', [InvitationController::class, 'process'])->name('invitation.process')
            ->middleware('guest');
});

/**
 * Stripe payment
 */
Route::prefix('payment')->group(function () {
    Route::get('/', [PaymentController::class, 'form'])->name('payment.form');
    Route::post('/', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/error', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/offline', [PaymentController::class, 'offlineForm'])->name('payment.offline-form');
    Route::post('/offline', [PaymentController::class, 'offline'])->name('payment.offline');
})->middleware('auth');
Route::post('/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm')
    ->middleware('stripe');

// All subsequent routes require a login of some sort
Route::middleware('auth')->group(function () {
    /**
     * Breeze profile editing
     */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    });

    /**
     * All these are admin only
     */
    Route::middleware('auth.admin')->group(function () {
        /**
         * Campaign request management
         */
        Route::post('/campaign/{campaign}/request', [CampaignController::class, 'newRequest'])->name('campaign.new-request');
        Route::delete('/campaign/{campaign}/request', [CampaignController::class, 'deleteRequest'])->name('campaign.delete-request');
        Route::post('/campaign/{campaign}/remind', [CampaignController::class, 'remindRequest'])->name('campaign.remind-request');
        Route::patch('/campaign/{campaign}/close', [CampaignController::class, 'close'])->name('campaign.close');

        Route::resources(
            [
                'property' => PropertyController::class,
                'member' => MemberController::class,
                'fund' => FundController::class,
                'campaign' => CampaignController::class,
            ]
        );
    });

    /**
     * Ledger management
     */
    Route::prefix('ledger')->group(function () {
        Route::get('/', [LedgerController::class, 'index'])->name('ledger.index');
        Route::get('/new', [LedgerController::class, 'create'])->name('ledger.create');
        Route::post('/', [LedgerController::class, 'store'])->name('ledger.store');
        Route::patch('/{ledger}', [LedgerController::class, 'verify'])->name('ledger.verify')
             ->middleware('auth.admin');
        Route::delete('/{ledger}', [LedgerController::class, 'destroy'])->name('ledger.destroy')
             ->middleware('auth.admin');
    });
});

require __DIR__.'/auth.php';
