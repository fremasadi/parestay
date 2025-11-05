<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PemilikController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\Pemilik\KostController as PemilikKostController;
use App\Http\Controllers\Front\LandingController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\PenyewaController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('pemilik', PemilikController::class);
    Route::resource('kost', KostController::class);
    Route::resource('penyewa',PenyewaController::class);
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/statistics', [App\Http\Controllers\Admin\ReviewController::class, 'statistics'])->name('reviews.statistics');
    Route::get('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

});

Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::resource('kost', PemilikKostController::class);
    Route::get('/reviews', [App\Http\Controllers\Pemilik\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/statistics', [App\Http\Controllers\Pemilik\ReviewController::class, 'statistics'])->name('reviews.statistics');
    Route::get('/reviews/kost/{kost}', [App\Http\Controllers\Pemilik\ReviewController::class, 'byKost'])->name('reviews.by-kost');
    Route::get('/reviews/{review}', [App\Http\Controllers\Pemilik\ReviewController::class, 'show'])->name('reviews.show');
});

Route::get('/', [FrontController::class, 'landing'])->name('landing');
Route::get('/detail/{id}', [FrontController::class, 'show'])->name('detail');
Route::get('/search', [FrontController::class, 'search'])->name('kost.search');

// API untuk mendapatkan data kost dalam format JSON (untuk peta)
Route::get('/api/kosts', [FrontController::class, 'getKostsJson'])->name('api.kosts');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
