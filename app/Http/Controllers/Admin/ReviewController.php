<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['kost', 'reviewer'])
            ->latest()
            ->paginate(10);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['kost', 'reviewer']);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus!');
    }

    // Statistik review
    public function statistics()
    {
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating');
        $ratingDistribution = Review::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();
        
        $topRatedKosts = Kost::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reviews.statistics', compact(
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'topRatedKosts'
        ));
    }
}