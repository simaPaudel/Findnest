<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
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
