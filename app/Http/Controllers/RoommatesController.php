<?php

namespace App\Http\Controllers;

use App\Models\RoommatePreference;
use App\Services\RoommateMatchingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoommatesController extends Controller
{
    public function __construct(private readonly RoommateMatchingService $matchingService)
    {
    }

    /**
     * Display the roommates landing page.
     *
     * Logged-in users are routed into the preference form or matches view.
     */
    public function index(): View
    {
        if (!Auth::check()) {
            return view('roommates.index');
        }

        return $this->renderRoommateFlow();
    }

    /**
     * Display the authenticated user's roommate flow.
     *
     * @return \Illuminate\View\View
     */
    public function profile(): View
    {
        return $this->renderRoommateFlow();
    }

    /**
     * Display roommate matches for authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function matches(): View
    {
        return $this->renderRoommateFlow();
    }

    private function renderRoommateFlow(): View
    {
        if (!Auth::check()) {
            return view('roommates.index');
        }

        $user = Auth::user();
        $preference = RoommatePreference::queryForUserId($user->id)->first();

        if (!$preference) {
            return view('user.roommate.edit', compact('preference'));
        }

        $matches = collect($this->matchingService->getRoommateMatches($user->id));

        return view('roommates.matches', [
            'preference' => $preference,
            'matches' => $matches,
        ]);
    }
}
