<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->where('status', 'selesai')
            ->findOrFail($bookingId);

        $existing = Review::where('kost_id', $booking->kost_id)
            ->where('reviewer_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk kost ini.');
        }

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'kost_id'     => $booking->kost_id,
            'reviewer_id' => auth()->id(),
            'rating'      => $request->rating,
            'komentar'    => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}
