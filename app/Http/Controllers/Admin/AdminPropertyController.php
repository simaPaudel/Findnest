<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::with('owner')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('city'), function ($query) use ($request) {
                $query->where('city', 'like', '%' . $request->city . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    public function approve(Property $property)
    {
        $originalStatus = $property->status;

        $property->update(['status' => 'approved']);

        if ($originalStatus !== 'approved' && $property->owner_id) {
            try {
                NotificationService::sendNotification(
                    (int) $property->owner_id,
                    'property',
                    'Listing approved',
                    'Your property listing has been approved.',
                    route('owner.listings.index')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block admin actions.
            }
        }

        return back()->with('success', 'Property approved successfully.');
    }

    public function reject(Property $property)
    {
        $originalStatus = $property->status;

        $property->update(['status' => 'rejected']);

        if ($originalStatus !== 'rejected' && $property->owner_id) {
            try {
                NotificationService::sendNotification(
                    (int) $property->owner_id,
                    'property',
                    'Listing rejected',
                    'Your property listing has been rejected.',
                    route('owner.listings.index')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block admin actions.
            }
        }

        return back()->with('success', 'Property rejected successfully.');
    }

    public function verify(Property $property)
    {
        $property->update([
            'is_verified' => ! $property->is_verified,
        ]);

        return back()->with('success', 'Property verification updated successfully.');
    }
}
