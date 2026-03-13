<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SavedListingController extends Controller
{
    /**
     * Display user's saved listings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $savedListings = collect(); // Empty collection by default

        // Check if saved_listings table exists
        if (Schema::hasTable('saved_listings')) {
            // TODO: When saved_listings table is created, uncomment and implement:
            // $savedListings = DB::table('saved_listings')
            //     ->join('properties', 'saved_listings.property_id', '=', 'properties.id')
            //     ->where('saved_listings.user_id', auth()->id())
            //     ->select('properties.*', 'saved_listings.created_at as saved_at')
            //     ->orderBy('saved_listings.created_at', 'desc')
            //     ->get();
        }

        return view('user.saved.index', compact('savedListings'));
    }
}
