<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnerApplication;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OwnerApplicationController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $searchTerm = trim((string) $request->query('q', ''));
        $selectedRole = $request->query('role');
        $roleOptions = [
            User::ROLE_USER => 'User',
            User::ROLE_OWNER => 'Owner',
        ];

        $applications = OwnerApplication::query()
            ->with('user')
            ->when($searchTerm !== '', function ($query) use ($searchTerm): void {
                $like = '%' . $searchTerm . '%';

                $query->where(function ($searchQuery) use ($like): void {
                    $searchQuery->where('full_name', 'like', $like)
                        ->orWhereHas('user', function ($userQuery) use ($like): void {
                            $userQuery->where('name', 'like', $like);
                        });
                });
            })
            ->when(in_array($selectedRole, [User::ROLE_USER, User::ROLE_OWNER], true), function ($query) use ($selectedRole): void {
                $query->whereHas('user', function ($userQuery) use ($selectedRole): void {
                    $userQuery->where('role', $selectedRole);
                });
            })
            ->orderByDesc('submitted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.owner-applications.index', compact(
            'applications',
            'searchTerm',
            'selectedRole',
            'roleOptions'
        ));
    }

    public function show(OwnerApplication $ownerApplication)
    {
        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $ownerApplication->load('user');

        return view('admin.owner-applications.show', compact('ownerApplication'));
    }

    public function approve(Request $request, OwnerApplication $ownerApplication)
    {
        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $data = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $applicationUser = $ownerApplication->user;
        $wasAlreadyApproved = $ownerApplication->isApproved();

        DB::transaction(function () use ($ownerApplication, $data): void {
            $ownerApplication->update([
                'status' => OwnerApplication::STATUS_APPROVED,
                'admin_notes' => filled($data['admin_notes'] ?? null) ? trim($data['admin_notes']) : null,
                'reviewed_at' => now(),
            ]);

            $ownerApplication->user()->update([
                'role' => User::ROLE_OWNER,
            ]);
        });

        if ($applicationUser && ! $wasAlreadyApproved) {
            try {
                NotificationService::sendNotification(
                    $applicationUser->id,
                    'host_application',
                    'Host application approved',
                    'Your host application has been approved. You can now access owner features.',
                    route('owner.dashboard')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block the approval flow.
            }
        }

        return redirect()
            ->route('admin.owner-applications.show', $ownerApplication)
            ->with('success', 'Owner application approved successfully.');
    }

    public function reject(Request $request, OwnerApplication $ownerApplication)
    {
        if (!Schema::hasTable('owner_applications')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Host applications are not available yet. Please try again after setup is complete.');
        }

        $data = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $applicationUser = $ownerApplication->user;
        $wasAlreadyRejected = $ownerApplication->isRejected();

        $ownerApplication->update([
            'status' => OwnerApplication::STATUS_REJECTED,
            'admin_notes' => filled($data['admin_notes'] ?? null) ? trim($data['admin_notes']) : null,
            'reviewed_at' => now(),
        ]);

        if ($applicationUser && ! $wasAlreadyRejected) {
            try {
                NotificationService::sendNotification(
                    $applicationUser->id,
                    'host_application',
                    'Host application rejected',
                    'Your host application was not approved. Please review the admin notes and submit again if needed.',
                    route('user.host-application.show')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block the rejection flow.
            }
        }

        return redirect()
            ->route('admin.owner-applications.show', $ownerApplication)
            ->with('success', 'Owner application rejected successfully.');
    }
}
