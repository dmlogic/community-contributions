<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;

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


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::prefix('property')->middleware('can:manage,App\Models\Property')->group(function () {
        Route::get('/', [PropertyController::class, 'list'])->name('property.list');
        Route::get('/{property}', [PropertyController::class, 'edit'])->name('property.edit');
        Route::patch('/{property}', [PropertyController::class, 'update'])->name('property.update');
        Route::delete('/{property}', [PropertyController::class, 'update'])->name('property.delete');
    });
});

require __DIR__.'/auth.php';
