<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class OwnerReviewController extends Controller
{
    /**
     * Display a listing of reviews for owner's properties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $owner = Auth::user();

        // Get owner's property IDs
        $propertyIds = Property::where('owner_id', $owner->id)->pluck('id');

        // Get reviews for owner's properties with relationships
        $reviews = Review::whereIn('property_id', $propertyIds)
            ->with(['property', 'user'])
            ->latest()
            ->paginate(20);

        // Calculate average rating
        $avgRating = Review::whereIn('property_id', $propertyIds)->avg('rating') ?? 0;

        return view('owner.reviews.index', compact('reviews', 'avgRating'));
    }
}
