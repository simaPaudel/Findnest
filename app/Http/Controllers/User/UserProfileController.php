<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UserProfileController extends Controller
{
    /**
     * Show the form for editing user profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $ownerApplication = Schema::hasTable('owner_applications')
            ? $user->ownerApplications()->latest('id')->first()
            : null;

        return view('user.profile.edit', compact('user', 'ownerApplication'));
    }

    /**
     * Update user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->input('profile_action') === 'password') {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            ], [
                'current_password.required' => 'Please enter your current password.',
                'password.required' => 'Please enter a new password.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.mixed' => 'Password must contain at least one uppercase letter and one lowercase letter.',
                'password.numbers' => 'Password must contain at least one number.',
                'password.symbols' => 'Password must contain at least one special character.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('password_modal_open', true);
            }

            $validated = $validator->validated();

            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->with('password_modal_open', true);
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('user.profile.edit')
                ->with('success', 'Password updated successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            // Create profiles directory if not exists
            $profilesPath = public_path('profiles');
            if (!file_exists($profilesPath)) {
                mkdir($profilesPath, 0755, true);
            }

            // Store new photo
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move($profilesPath, $filename);
            $validated['profile_photo'] = 'profiles/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
