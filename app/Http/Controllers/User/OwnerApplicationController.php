<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OwnerApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OwnerApplicationController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('user.profile.edit')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $application = $user->ownerApplications()->latest('id')->first();

        return view('user.profile.become-host', compact('user', 'application'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('user.profile.edit')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $latestApplication = $user->ownerApplications()->latest('id')->first();

        if ($user->role === User::ROLE_OWNER) {
            return redirect()->route('owner.dashboard')
                ->with('success', 'Your host request has already been approved.');
        }

        if ($latestApplication && $latestApplication->isPending()) {
            return redirect()->route('user.host-application.show')
                ->with('error', 'You already have a pending host application.');
        }

        if ($latestApplication && $latestApplication->isApproved()) {
            return redirect()->route('owner.dashboard')
                ->with('success', 'Your host application is already approved.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'citizenship_number' => 'required|string|max:100',
            'citizenship_front' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'citizenship_back' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'address' => 'required|string|max:1000',
        ]);

        $frontPath = $request->file('citizenship_front')->store('owner-applications', 'public');
        $backPath = $request->file('citizenship_back')->store('owner-applications', 'public');

        $user->ownerApplications()->create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'citizenship_number' => $validated['citizenship_number'],
            'citizenship_front' => $frontPath,
            'citizenship_back' => $backPath,
            'address' => $validated['address'],
            'status' => OwnerApplication::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

        return redirect()->route('user.host-application.show')
            ->with('success', 'Your host application has been submitted for review.');
    }
}
