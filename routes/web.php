<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundController;
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
    dd(\App\Enums\Entry::RESIDENT_REQUEST);
});

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::middleware('auth.admin')->group(function () {
        Route::resources(
            [
                'property' => PropertyController::class,
                'member' => MemberController::class,
                'fund' => FundController::class,
            ]
        );

        Route::prefix('invitation')->group(function() {
            Route::post('/', [InvitationController::class, 'store'])->name('invitation.store');
        });
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/invitation/{invitation}', [InvitationController::class, 'confirm'])->name('invitation.confirm');
    Route::post('/invitation/{invitation}', [InvitationController::class, 'process'])->name('invitation.process');
});

require __DIR__.'/auth.php';
