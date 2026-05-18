<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->withCount(['properties', 'bookings', 'reviews', 'savedListings'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));

                $query->where(function ($searchQuery) use ($term) {
                    $searchQuery->where('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('is_blocked', false);
                } elseif ($request->status === 'blocked') {
                    $query->where('is_blocked', true);
                }
            })
            ->when($request->filled('verification'), function ($query) use ($request) {
                if ($request->verification === 'verified') {
                    $query->where('is_verified', true);
                } elseif ($request->verification === 'unverified') {
                    $query->where('is_verified', false);
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return $this->renderProfile($user);
    }

    public function edit(User $user)
    {
        return $this->renderProfile($user, true);
    }

    private function renderProfile(User $user, bool $editMode = false)
    {
        $user->loadCount(['properties', 'bookings', 'reviews', 'savedListings']);
        $recentProperties = $user->isOwner()
            ? $user->properties()
                ->with('images')
                ->latest()
                ->take(3)
                ->get()
            : collect();

        $recentBookings = $user->isOwner()
            ? collect()
            : $user->bookings()
                ->with(['property.images'])
                ->latest()
                ->take(3)
                ->get();

        $recentReviews = $user->reviews()
            ->with(['property.images'])
            ->latest()
            ->take(3)
            ->get();

        $recentReports = Report::query()
            ->with(['reporter', 'reportable', 'reviewedByUser'])
            ->where(function ($query) use ($user) {
                $query->where('reporter_id', $user->id)
                    ->orWhere('reviewed_by', $user->id)
                    ->orWhere(function ($reportQuery) use ($user) {
                        $reportQuery->where('reportable_type', User::class)
                            ->where('reportable_id', $user->id);
                    });
            })
            ->recent()
            ->take(3)
            ->get();

        $successfulPaymentsCount = $user->payments()
            ->where('payment_status', 'success')
            ->count();

        $successfulPaymentsAmount = $user->payments()
            ->where('payment_status', 'success')
            ->sum('amount');

        return view('admin.users.show', compact(
            'user',
            'recentProperties',
            'recentBookings',
            'recentReviews',
            'recentReports',
            'successfulPaymentsCount',
            'successfulPaymentsAmount',
            'editMode'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['nullable', Rule::in([User::ROLE_USER, User::ROLE_OWNER])],
            'is_blocked' => ['nullable', 'boolean'],
        ]);

        $updates = [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ];

        $canManageProtectedFields = ! $user->isAdmin() && (int) auth()->id() !== (int) $user->id;

        if ($canManageProtectedFields && isset($validated['role'])) {
            $updates['role'] = $validated['role'];
        }

        if ($canManageProtectedFields) {
            $updates['is_blocked'] = (bool) ($validated['is_blocked'] ?? false);
        }

        $user->update($updates);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User details updated successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin roles cannot be changed from here.');
        }

        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'You cannot change your own role from here.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_OWNER])],
        ]);

        if ($user->role === $validated['role']) {
            return back()->with('success', 'Role is already up to date.');
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin accounts cannot be deactivated.');
        }

        if ((int) auth()->id() === (int) $user->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'is_blocked' => ! $user->is_blocked,
        ]);

        return back()->with(
            'success',
            $user->is_blocked
                ? 'User deactivated successfully.'
                : 'User reactivated successfully.'
        );
    }
}
