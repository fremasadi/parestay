<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Daftar semua review untuk kost milik pemilik
    public function index()
    {
        $pemilik = Auth::user()->owner;
        
        if (!$pemilik) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pemilik tidak ditemukan.');
        }

        $reviews = Review::whereHas('kost', function ($query) use ($pemilik) {
            $query->where('owner_id', $pemilik->id);
        })
        ->with(['kost', 'reviewer'])
        ->latest()
        ->paginate(10);

        return view('pemilik.reviews.index', compact('reviews'));
    }

    // Detail review
    public function show(Review $review)
    {
        $pemilik = Auth::user()->owner;
        
        // Cek apakah review ini milik kost pemilik
        if ($review->kost->owner_id !== $pemilik->id) {
            abort(403, 'Anda tidak memiliki akses ke review ini.');
        }

        $review->load(['kost', 'reviewer']);
        return view('pemilik.reviews.show', compact('review'));
    }

    // Statistik review untuk semua kost pemilik
    public function statistics()
    {
        $pemilik = Auth::user()->owner;
        
        if (!$pemilik) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pemilik tidak ditemukan.');
        }

        // Total review untuk semua kost pemilik
        $totalReviews = Review::whereHas('kost', function ($query) use ($pemilik) {
            $query->where('owner_id', $pemilik->id);
        })->count();

        // Rata-rata rating
        $averageRating = Review::whereHas('kost', function ($query) use ($pemilik) {
            $query->where('owner_id', $pemilik->id);
        })->avg('rating');

        // Distribusi rating
        $ratingDistribution = Review::whereHas('kost', function ($query) use ($pemilik) {
            $query->where('owner_id', $pemilik->id);
        })
        ->selectRaw('rating, COUNT(*) as count')
        ->groupBy('rating')
        ->orderBy('rating', 'desc')
        ->get();

        // Kost dengan rating terbaik milik pemilik
        $myKosts = Kost::where('owner_id', $pemilik->id)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->orderBy('reviews_avg_rating', 'desc')
            ->get();

        // Review terbaru
        $recentReviews = Review::whereHas('kost', function ($query) use ($pemilik) {
            $query->where('owner_id', $pemilik->id);
        })
        ->with(['kost', 'reviewer'])
        ->latest()
        ->limit(10)
        ->get();

        return view('pemilik.reviews.statistics', compact(
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'myKosts',
            'recentReviews'
        ));
    }

    // Review per kost
    public function byKost(Kost $kost)
    {
        $pemilik = Auth::user()->owner;
        
        // Cek apakah kost ini milik pemilik
        if ($kost->owner_id !== $pemilik->id) {
            abort(403, 'Anda tidak memiliki akses ke kost ini.');
        }

        $reviews = $kost->reviews()
            ->with('reviewer')
            ->latest()
            ->paginate(10);

        $averageRating = $kost->reviews()->avg('rating');
        $totalReviews = $kost->reviews()->count();

        return view('pemilik.reviews.by-kost', compact('kost', 'reviews', 'averageRating', 'totalReviews'));
    }
}