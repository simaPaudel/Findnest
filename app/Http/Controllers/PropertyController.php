<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with(['owner', 'rooms'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'rent_price' => 'required|numeric|min:0',
            'room_type' => 'required|in:single,shared,flat',
            'gender_preference' => 'required|in:any,male,female',
            'total_rooms' => 'required|integer|min:1'
        ]);

        Property::create([
            'owner_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'location' => $request->location,
            'landmark' => $request->landmark,
            'rent_price' => $request->rent_price,
            'room_type' => $request->room_type,
            'gender_preference' => $request->gender_preference,
            'furnished' => $request->furnished ?? false,
            'total_rooms' => $request->total_rooms,
            'amenities' => $request->amenities ? json_encode($request->amenities) : null,
            'photos' => $request->photos ? json_encode($request->photos) : null,
            'rules' => $request->rules,
        ]);

        return redirect()->route('properties.index')
            ->with('success', 'Property listed successfully!');
    }

    public function show($id)
    {
        $property = Property::with(['owner', 'rooms', 'reviews.student'])
            ->findOrFail($id);

        return view('properties.show', compact('property'));
    }

    public function edit($id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'rent_price' => 'sometimes|numeric|min:0',
        ]);

        $property->update($request->all());

        return redirect()->route('properties.index')
            ->with('success', 'Property updated successfully!');
    }

    public function destroy($id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}