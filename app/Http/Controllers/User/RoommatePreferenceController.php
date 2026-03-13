<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RoommatePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoommatePreferenceController extends Controller
{
    /**
     * Show the form for editing roommate preferences.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $preference = RoommatePreference::where('user_id', Auth::id())->first();
        return view('user.roommate.edit', compact('preference'));
    }

    /**
     * Store or update roommate preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_range' => 'nullable|string|max:50',
            'preferred_location' => 'nullable|string|max:255',
            'cleanliness_level' => 'nullable|in:very_clean,clean,moderate,relaxed',
            'sleep_schedule' => 'nullable|in:early_bird,night_owl,flexible',
            'study_habits' => 'nullable|in:quiet,moderate,social',
            'gender_preference' => 'nullable|in:male,female,any',
            'smoking_preference' => 'nullable|in:yes,no,outside_only',
            'alcohol_preference' => 'nullable|in:yes,no,occasionally',
            'max_roommates' => 'nullable|integer|min:1|max:10',
            'age_range_min' => 'nullable|integer|min:18|max:100',
            'age_range_max' => 'nullable|integer|min:18|max:100',
            'interests' => 'nullable|string|max:500',
            'additional_preferences' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        RoommatePreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return redirect()->route('user.roommate-preferences.edit')
            ->with('success', 'Roommate preferences saved successfully.');
    }
}
