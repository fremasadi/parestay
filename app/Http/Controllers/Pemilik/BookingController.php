<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pastikan user punya data pemilik
        if (!$user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $user->pemilik->id;

        $bookings = Booking::with(['kost', 'kamar', 'user'])
            ->where('status', 'aktif')
            ->whereHas('kost', function ($query) use ($pemilikId) {
                $query->where('owner_id', $pemilikId);
            })
            ->latest()
            ->paginate(10);

        return view('pemilik.booking.index', compact('bookings'));
    }
}
