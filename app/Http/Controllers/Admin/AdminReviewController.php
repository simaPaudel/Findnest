<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['property', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => 1]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function hide(Review $review)
    {
        $review->update(['is_approved' => 0]);

        return back()->with('success', 'Review hidden successfully.');
    }
}
