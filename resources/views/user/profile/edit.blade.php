@extends('user.layout')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="fn-glass-card p-8">
    <h2 class="text-2xl font-bold fn-text-charcoal mb-6">Edit Your Profile</h2>

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium fn-text-charcoal mb-2">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium fn-text-charcoal mb-2">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="+977 9812345678">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium fn-text-charcoal mb-2">Gender</label>
                <select id="gender" name="gender" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Photo -->
            <div>
                <label for="profile_photo" class="block text-sm font-medium fn-text-charcoal mb-2">Profile Photo</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                @if($user->profile_photo)
                    <div class="mt-2">
                        <img src="{{ asset($user->profile_photo) }}" alt="Current profile photo" class="w-20 h-20 rounded-full object-cover">
                    </div>
                @endif
                @error('profile_photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs fn-text-gray mt-1">Max 2MB, JPG/PNG only</p>
            </div>
        </div>

        <!-- Bio -->
        <div class="mt-6">
            <label for="bio" class="block text-sm font-medium fn-text-charcoal mb-2">Bio</label>
            <textarea id="bio" name="bio" rows="4" 
                      class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs fn-text-gray mt-1">Maximum 500 characters</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="fn-btn-primary">
                Update Profile
            </button>
            <a href="{{ route('user.dashboard') }}" class="fn-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
