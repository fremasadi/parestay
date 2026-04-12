<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class HistoryController extends Controller
{


    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Booking::with(['kost', 'pembayaran'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $bookings = $query->paginate(10);
        
        // Hitung statistik
        $stats = [
            'total' => Booking::where('user_id', auth()->id())->count(),
            'aktif' => Booking::where('user_id', auth()->id())->where('status', 'aktif')->count(),
            'pending' => Booking::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'selesai' => Booking::where('user_id', auth()->id())->where('status', 'selesai')->count(),
        ];
        
        $reviewedKostIds = Review::where('reviewer_id', auth()->id())
            ->pluck('kost_id')
            ->toArray();

        return view('front.history.index', compact('bookings', 'stats', 'status', 'reviewedKostIds'));
    }

    public function show($id)
    {
        $booking = Booking::with(['kost', 'pembayaran'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        
        $hasReviewed = Review::where('kost_id', $booking->kost_id)
            ->where('reviewer_id', auth()->id())
            ->exists();

        return view('front.history.show', compact('booking', 'hasReviewed'));
    }

    public function cancel($id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);
        
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }
        
        $booking->update(['status' => 'dibatalkan']);
        
        // Kembalikan slot kost
        $booking->kost->increment('slot_tersedia');
        
        return back()->with('success', 'Booking berhasil dibatalkan');
    }
}