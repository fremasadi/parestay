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
use App\Http\Controllers\Front\HistoryController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Pemilik\BookingController as PemilikBookingController;
use App\Http\Controllers\Pemilik\PembayaranController as PemilikPembayaranController;
use App\Http\Controllers\Admin\KursusController;

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
    Route::resource('booking', AdminBookingController::class);
        Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');

    Route::resource('kursus', KursusController::class);


});

Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::resource('kost', PemilikKostController::class);
    Route::get('/reviews', [App\Http\Controllers\Pemilik\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/statistics', [App\Http\Controllers\Pemilik\ReviewController::class, 'statistics'])->name('reviews.statistics');
    Route::get('/reviews/kost/{kost}', [App\Http\Controllers\Pemilik\ReviewController::class, 'byKost'])->name('reviews.by-kost');
    Route::get('/reviews/{review}', [App\Http\Controllers\Pemilik\ReviewController::class, 'show'])->name('reviews.show');

        Route::get('/booking', [PemilikBookingController::class, 'index'])->name('booking.index');
    Route::get('/pembayaran', [PemilikPembayaranController::class, 'index'])->name('pembayaran.index');

});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::get('/booking/create/{kost}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store/{kost}', [BookingController::class, 'store'])->name('booking.store');
    
    // User's Bookings
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    
    // Payment Routes
    Route::get('/payment/create/{bookingId}', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/check/{id}', [PaymentController::class, 'checkStatus'])->name('payment.check');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    Route::post('/history/{id}/cancel', [HistoryController::class, 'cancel'])->name('history.cancel');
});
// Payment Callback & Finish (No Auth Required)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

require __DIR__.'/auth.php';
