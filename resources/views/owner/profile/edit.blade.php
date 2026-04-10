@extends('owner.layout')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Profile Photo -->
            <div class="form-section">
                <h3 class="form-section-title">Profile Photo</h3>

                <div class="profile-photo-section">
                    @if($owner->profile_photo)
                        <img src="{{ asset('storage/' . $owner->profile_photo) }}" alt="Profile" class="profile-photo-preview">
                    @else
                        <div class="profile-photo-placeholder">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="profile_photo" class="form-label">Update Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-input @error('profile_photo') error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                        <small class="form-hint">Max size: 2MB. Accepted formats: JPG, PNG, WEBP</small>
                        @error('profile_photo')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="form-section-title">Personal Information</h3>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" name="name" id="name" class="form-input @error('name') error @enderror" value="{{ old('name', $owner->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-input" value="{{ $owner->email }}" disabled>
                    <small class="form-hint">Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-input @error('phone') error @enderror" value="{{ old('phone', $owner->phone) }}">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" id="bio" rows="4" class="form-input @error('bio') error @enderror" maxlength="1000">{{ old('bio', $owner->bio) }}</textarea>
                    <small class="form-hint">Maximum 1000 characters</small>
                    @error('bio')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('owner.dashboard') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Profile</button>
        </div>
    </form>

    <div class="form-actions">
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-danger-outline">Logout</button>
        </form>
    </div>
</div>
@endsection
