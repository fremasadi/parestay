<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'status' => 'nullable|in:pending,aktif,selesai,dibatalkan',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        // Pastikan user punya data pemilik
        if (! $user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $user->pemilik->id;

        $bookings = Booking::with(['kost', 'kamar', 'user'])
            ->whereHas('kost', function ($query) use ($pemilikId) {
                $query->where('owner_id', $pemilikId);
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($validated['tanggal_dari'] ?? null, function ($query, $tanggalDari) {
                $query->whereDate('tanggal_mulai', '>=', $tanggalDari);
            })
            ->when($validated['tanggal_sampai'] ?? null, function ($query, $tanggalSampai) {
                $query->whereDate('tanggal_mulai', '<=', $tanggalSampai);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.booking.index', compact('bookings'));
    }
}
