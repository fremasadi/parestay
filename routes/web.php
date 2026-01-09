<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PemilikController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\Admin\PenyewaController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\KursusController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// Pemilik Controllers
use App\Http\Controllers\Pemilik\KostController as PemilikKostController;
use App\Http\Controllers\Pemilik\BookingController as PemilikBookingController;
use App\Http\Controllers\Pemilik\PembayaranController as PemilikPembayaranController;
use App\Http\Controllers\Pemilik\ReviewController as PemilikReviewController;
use App\Http\Controllers\Pemilik\KamarController as PemilikKamarController;

// Front / Public Controllers
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\HistoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public / Front Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [FrontController::class, 'landing'])->name('landing');

// Detail kost
Route::get('/detail/{id}', [FrontController::class, 'show'])->name('detail');

// Search kost (filter)
Route::get('/search', [FrontController::class, 'search'])->name('kost.search');

// API JSON untuk peta Leaflet
Route::get('/api/kosts', [FrontController::class, 'getKostsJson'])->name('api.kosts');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Resource routes untuk User, Pemilik, Kost, Penyewa
    Route::resource('users', UserController::class);          // admin.users.*
    Route::resource('pemilik', PemilikController::class);     // admin.pemilik.*
    Route::resource('kost', KostController::class);           // admin.kost.*
    Route::resource('penyewa', PenyewaController::class);     // admin.penyewa.*

    // Reviews Admin
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/statistics', [AdminReviewController::class, 'statistics'])->name('reviews.statistics');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Booking & Pembayaran Admin
    Route::resource('booking', AdminBookingController::class);                   // admin.booking.*
    Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');

    // Kursus Admin
    Route::resource('kursus', KursusController::class);                           // admin.kursus.*
});

/*
|--------------------------------------------------------------------------
| Pemilik Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {

    // Resource Kost Pemilik
    Route::resource('kost', PemilikKostController::class);
    Route::resource('kamar', PemilikKamarController::class);                     // pemilik.kost.*

    // Reviews Pemilik
    Route::get('/reviews', [PemilikReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/statistics', [PemilikReviewController::class, 'statistics'])->name('reviews.statistics');
    Route::get('/reviews/kost/{kost}', [PemilikReviewController::class, 'byKost'])->name('reviews.by-kost');
    Route::get('/reviews/{review}', [PemilikReviewController::class, 'show'])->name('reviews.show');

    // Booking & Pembayaran Pemilik
    Route::get('/booking', [PemilikBookingController::class, 'index'])->name('booking.index');
    Route::get('/pembayaran', [PemilikPembayaranController::class, 'index'])->name('pembayaran.index');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking User
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Payment
    Route::get('/payment/create/{bookingId}', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/check/{id}', [PaymentController::class, 'checkStatus'])->name('payment.check');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');

    // History Booking User
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    Route::post('/history/{id}/cancel', [HistoryController::class, 'cancel'])->name('history.cancel');
});

/*
|--------------------------------------------------------------------------
| Payment Callback (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
