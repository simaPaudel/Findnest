<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::with(['owner', 'images', 'rooms.images'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('city'), function ($query) use ($request) {
                $query->where('city', 'like', '%' . $request->city . '%');
            })
            ->when($request->filled('user'), function ($query) use ($request) {
                $userId = (int) $request->integer('user');

                $query->where(function ($relatedQuery) use ($userId) {
                    $relatedQuery->where('owner_id', $userId)
                        ->orWhereHas('bookings', function ($bookingQuery) use ($userId) {
                            $bookingQuery->where('user_id', $userId);
                        });
                });
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

    public function destroy(Property $property)
    {
        try {
            DB::beginTransaction();

            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            foreach ($property->rooms as $room) {
                foreach ($room->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            $property->rooms()->delete();
            $property->delete();

            DB::commit();

            return back()->with('success', 'Property removed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to remove property. Please try again.');
        }
    }
}
