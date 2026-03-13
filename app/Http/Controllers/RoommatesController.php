<?php

namespace App\Http\Controllers;

class RoommatesController extends Controller
{
    /**
     * Display the roommates listing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('roommates.index');
    }

    /**
     * Display the authenticated user's roommate profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('roommates.profile');
    }

    /**
     * Display roommate matches for authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function matches()
    {
        return view('roommates.matches');
    }
}
