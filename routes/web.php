<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {

    /**
     * Breeze profile editing
     */
    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    });

    /**
     * Adding ledger entries for fund contributions
     */
    Route::prefix('ledger')->group(function(){
        Route::post('/', [LedgerController::class, 'store'])->name('ledger.store');
    });

    /**
     * Admin only routes
     */
    Route::middleware('auth.admin')->group(function () {
        /**
         * Admin CRUD activity
         */
        Route::resources(
            [
                'property' => PropertyController::class,
                'member' => MemberController::class,
                'fund' => FundController::class,
            ]
        );

        /**
         * Additional route for member invitation creation
         */
        Route::prefix('invitation')->group(function() {
            Route::post('/', [InvitationController::class, 'store'])->name('invitation.store');
        });

        /**
         * Additional ledger entry adnin
         */
        Route::patch('/ledger/{ledger}', [LedgerController::class, 'verify'])->name('ledger.verify');
        Route::delete('/ledger/{ledger}', [LedgerController::class, 'destroy'])->name('ledger.destroy');
    });
});

/**
 * Member invitation handling
 */
Route::middleware('guest')->group(function () {
    Route::get('/invitation/{invitation}', [InvitationController::class, 'confirm'])->name('invitation.confirm');
    Route::post('/invitation/{invitation}', [InvitationController::class, 'process'])->name('invitation.process');
});

require __DIR__.'/auth.php';
