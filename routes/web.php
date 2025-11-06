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
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

Route::get('/', [FrontController::class, 'landing'])->name('landing');
Route::get('/detail/{id}', [FrontController::class, 'show'])->name('detail');
Route::get('/search', [FrontController::class, 'search'])->name('kost.search');

// API untuk mendapatkan data kost dalam format JSON (untuk peta)
Route::get('/api/kosts', [FrontController::class, 'getKostsJson'])->name('api.kosts');

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kost/{kost}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/kost/{kost}/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');

});

// Payment routes
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.status');

require __DIR__.'/auth.php';
