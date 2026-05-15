<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile edit form.
     */
    public function edit()
    {
        $admin = Auth::user();

        return view('admin.profile.edit', [
            'admin' => $admin,
            'photoUrl' => $this->photoUrl($admin->profile_photo),
        ]);
    }

    /**
     * Update the authenticated admin profile.
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

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

            if (! Hash::check($validated['current_password'], $admin->password)) {
                return back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->with('password_modal_open', true);
            }

            $admin->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('admin.profile.edit')
                ->with('success', 'Password updated successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($admin->profile_photo) {
                Storage::disk('public')->delete($admin->profile_photo);

                if (file_exists(public_path($admin->profile_photo))) {
                    @unlink(public_path($admin->profile_photo));
                }
            }

            $validated['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $admin->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $admin->phone,
            'gender' => $validated['gender'] ?? $admin->gender,
            'bio' => $validated['bio'] ?? $admin->bio,
            'profile_photo' => $validated['profile_photo'] ?? $admin->profile_photo,
        ]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Resolve a displayable photo URL across the legacy and storage paths.
     */
    private function photoUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('storage/' . ltrim($path, '/')))) {
            return asset('storage/' . ltrim($path, '/'));
        }

        if (file_exists(storage_path('app/public/' . ltrim($path, '/')))) {
            return asset('storage/' . ltrim($path, '/'));
        }

        if (Str::startsWith($path, 'profiles/')) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
