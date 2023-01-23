<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InvitationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/scratch', function () {
});

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Invitation handling
 */
Route::prefix('invitation')->group(function() {

    Route::post('/', [InvitationController::class, 'store'])->name('invitation.store')
            ->middleware(['auth', 'auth.admin']);
    Route::get('/{invitation}', [InvitationController::class, 'confirm'])->name('invitation.confirm')
            ->middleware('guest');
    Route::post('/{invitation}', [InvitationController::class, 'process'])->name('invitation.process')
            ->middleware('guest');
});

// All subsequent routes require a login of some sort
Route::middleware('auth')->group(function () {

    /**
     * Breeze profile editing
     */
    Route::prefix('profile')->group(function(){
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
    Route::prefix('ledger')->group(function(){
        Route::post('/', [LedgerController::class, 'store'])->name('ledger.store');
        Route::patch('/{ledger}', [LedgerController::class, 'verify'])->name('ledger.verify')
             ->middleware('auth.admin');
        Route::delete('/{ledger}', [LedgerController::class, 'destroy'])->name('ledger.destroy')
             ->middleware('auth.admin');
    });



});

require __DIR__.'/auth.php';
