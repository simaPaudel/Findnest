<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;

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

            if (! Hash::check($validated['current_password'], $owner->password)) {
                return back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->with('password_modal_open', true);
            }

            $owner->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('owner.profile.edit')
                ->with('success', 'Password updated successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'payout_method' => 'nullable|in:khalti,esewa,bank',
            'payout_account_name' => 'nullable|string|max:255',
            'payout_account_number' => 'nullable|string|max:255',
            'payout_qr' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'payout_notes' => 'nullable|string|max:2000',
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

        // Handle payout QR upload
        if ($request->hasFile('payout_qr')) {
            if ($owner->payout_qr) {
                Storage::disk('public')->delete($owner->payout_qr);
            }

            $qrPath = $request->file('payout_qr')->store('payout-qr', 'public');
            $validated['payout_qr'] = $qrPath;
        }

        // Update owner profile
        $owner->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $owner->phone,
            'gender' => $validated['gender'] ?? $owner->gender,
            'bio' => $validated['bio'] ?? $owner->bio,
            'profile_photo' => $validated['profile_photo'] ?? $owner->profile_photo,
            'payout_method' => $validated['payout_method'] ?? $owner->payout_method,
            'payout_account_name' => $validated['payout_account_name'] ?? $owner->payout_account_name,
            'payout_account_number' => $validated['payout_account_number'] ?? $owner->payout_account_number,
            'payout_qr' => $validated['payout_qr'] ?? $owner->payout_qr,
            'payout_notes' => $validated['payout_notes'] ?? $owner->payout_notes,
        ]);

        return redirect()
            ->route('owner.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
