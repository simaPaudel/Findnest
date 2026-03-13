<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerProfileController extends Controller
{
    /**
     * Show the form for editing the owner's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $owner = Auth::user();
        return view('owner.profile.edit', compact('owner'));
    }

    /**
     * Update the owner's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $owner = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($owner->profile_photo) {
                Storage::disk('public')->delete($owner->profile_photo);
            }

            // Store new photo
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
            $validated['profile_photo'] = $photoPath;
        }

        // Update owner profile
        $owner->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $owner->phone,
            'bio' => $validated['bio'] ?? $owner->bio,
            'profile_photo' => $validated['profile_photo'] ?? $owner->profile_photo,
        ]);

        return redirect()
            ->route('owner.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
