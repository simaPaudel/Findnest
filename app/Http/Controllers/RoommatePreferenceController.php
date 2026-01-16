<?php

namespace App\Http\Controllers;

use App\Models\RoommatePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoommatePreferenceController extends Controller
{
    public function show()
    {
        $preference = RoommatePreference::where('student_id', Auth::id())->first();
        return view('roommate-preferences.show', compact('preference'));
    }

    public function create()
    {
        return view('roommate-preferences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_range' => 'nullable|string|max:50',
            'preferred_location' => 'nullable|string',
            'cleanliness_level' => 'nullable|integer|min:1|max:5',
            'max_roommates' => 'nullable|integer|min:1'
        ]);

        RoommatePreference::updateOrCreate(
            ['student_id' => Auth::id()],
            $request->all()
        );

        return redirect()->route('roommate-preferences.show')
            ->with('success', 'Preferences saved successfully!');
    }

    public function edit()
    {
        $preference = RoommatePreference::where('student_id', Auth::id())->firstOrFail();
        return view('roommate-preferences.edit', compact('preference'));
    }

    public function update(Request $request)
    {
        $preference = RoommatePreference::where('student_id', Auth::id())->firstOrFail();

        $request->validate([
            'budget_range' => 'sometimes|string|max:50',
            'preferred_location' => 'nullable|string',
            'cleanliness_level' => 'sometimes|integer|min:1|max:5'
        ]);

        $preference->update($request->all());

        return redirect()->route('roommate-preferences.show')
            ->with('success', 'Preferences updated successfully!');
    }
}