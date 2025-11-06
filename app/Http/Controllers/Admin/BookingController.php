<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['kost', 'user'])->latest()->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['kost', 'user', 'pembayaran'])->findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}
