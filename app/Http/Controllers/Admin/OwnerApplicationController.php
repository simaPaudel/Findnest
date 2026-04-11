<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnerApplication;
use App\Models\User;
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

        $status = $request->string('status')->toString();

        $applications = OwnerApplication::query()
            ->with('user')
            ->when(in_array($status, [
                OwnerApplication::STATUS_PENDING,
                OwnerApplication::STATUS_APPROVED,
                OwnerApplication::STATUS_REJECTED,
            ], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->orderByDesc('submitted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.owner-applications.index', compact('applications', 'status'));
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

        $ownerApplication->update([
            'status' => OwnerApplication::STATUS_REJECTED,
            'admin_notes' => filled($data['admin_notes'] ?? null) ? trim($data['admin_notes']) : null,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.owner-applications.show', $ownerApplication)
            ->with('success', 'Owner application rejected successfully.');
    }
}
