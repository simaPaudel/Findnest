<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    /**
     * Display a listing of properties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Base query for approved properties
        $query = Property::where('status', 'approved');

        // Search by location keyword (city, location, address, or title)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qry) use ($q) {
                $qry->where('city', 'like', "%$q%")
                    ->orWhere('location', 'like', "%$q%")
                    ->orWhere('address', 'like', "%$q%")
                    ->orWhere('title', 'like', "%$q%");
            });
        }

        // Filter by max price
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('rent_price', '<=', $request->max_price);
        }

        // Filter by room type (optional)
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Filter by city (optional)
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Sorting (optional)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('rent_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('rent_price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // Paginate results
        $properties = $query->paginate(12)->appends($request->query());

        return view('listings.index', compact('properties'));
    }

    /**
     * Display the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\View\View
     */
    public function show(Property $property)
    {
        // Only show approved properties
        if ($property->status !== 'approved') {
            abort(404);
        }

        // Load owner relationship
        $property->load('owner');

        // Load approved property reviews with user relationship
        $reviews = $property->reviews()
            ->where('review_type', 'property')
            ->where('is_approved', 1)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate average rating and review count
        $avgRating = $reviews->avg('rating') ?? 0;
        $reviewCount = $reviews->count();

        // Fetch similar properties
        $similar = Property::where('status', 'approved')
            ->where('id', '!=', $property->id)
            ->where(function($q) use ($property) {
                $q->where('city', $property->city)
                  ->orWhere('room_type', $property->room_type);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('listings.show', compact('property', 'reviews', 'avgRating', 'reviewCount', 'similar'));
    }
}
