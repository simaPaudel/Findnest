@extends('user.layout')

@section('title', 'Edit Profile')
@section('page-title', 'Account Settings')

@section('content')
<style>
    .profile-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 48px;
        padding-bottom: 32px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f3f4f6;
        border: 3px solid #e5e7eb;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-avatar-placeholder svg {
        width: 56px;
        height: 56px;
        color: #9ca3af;
    }

    .profile-info h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .profile-info p {
        font-size: 0.95rem;
        color: #6b7280;
    }

    .photo-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 20px;
    }

    .btn-photo {
        background: #ff385c;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-photo:hover {
        background: #e11d48;
    }

    .photo-input-hidden {
        display: none;
    }

    .settings-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .form-row-single {
        grid-template-columns: 1fr;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: #1f2937;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #b0b8c1;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #ff385c;
        box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
    }

    .form-helper {
        font-size: 0.8rem;
        color: #8b92a0;
        margin-top: 6px;
    }

    .form-error {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 6px;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn-save {
        background: #ff385c;
        color: #ffffff;
        padding: 13px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        background: #e11d48;
    }

    .btn-cancel {
        background: transparent;
        color: #6b7280;
        padding: 13px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1.5px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-cancel:hover {
        border-color: #ff385c;
        color: #ff385c;
        background: rgba(255, 56, 92, 0.05);
    }

    .btn-logout {
        background: #fff5f7;
        color: #ff385c;
        padding: 13px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1.5px solid #ffd4dc;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-logout:hover {
        border-color: #ff385c;
        background: #fff1f4;
    }

    .logout-form {
        margin: 0;
    }

    .profile-logout-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .settings-section {
            padding: 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-save,
        .btn-cancel,
        .btn-logout,
        .logout-form {
            width: 100%;
            text-align: center;
        }

        .profile-logout-row {
            justify-content: flex-start;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Header with Picture and Update Button -->
    <div class="profile-header">
        <div class="profile-avatar">
            @if($user->profile_photo)
            <img src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }}" />
            @else
            <div class="profile-avatar-placeholder">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            @endif
        </div>

        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>Manage your account information and preferences</p>
        </div>

        <div class="photo-buttons">
            <button type="button" class="btn-photo" onclick="document.getElementById('profile_photo').click()">
                Update Photo
            </button>
        </div>
    </div>

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Hidden File Input for Photo -->
        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="photo-input-hidden" onchange="this.form.submit()">

        <!-- Personal Information Section -->
        <div class="settings-section">
            <h3 class="section-title">Personal Information</h3>

            <div class="form-row">
                <!-- Full Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="form-input" required>
                    @error('name')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="form-input" placeholder="you@example.com">
                    @error('email')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="form-input" placeholder="+977 9812345678">
                    @error('phone')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}"
                        class="form-input" placeholder="Your city or neighborhood">
                    @error('address')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label for="gender" class="form-label">Gender</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Bio Section -->
        <div class="settings-section">
            <h3 class="section-title">Bio</h3>

            <div class="form-group">
                <label for="bio" class="form-label">Tell us about yourself</label>
                <textarea id="bio" name="bio" class="form-textarea"
                    placeholder="Share your interests, hobbies, and what you're looking for in roommates...">{{ old('bio', $user->bio) }}</textarea>
                <p class="form-helper">Maximum 500 characters</p>
                @error('bio')
                <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
    <div class="action-buttons">
        <button type="submit" class="btn-save">
            Save Changes
        </button>
        <a href="{{ route('user.dashboard') }}" class="btn-cancel">
            Cancel
        </a>
    </div>
</form>

<div class="profile-logout-row">
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
    </form>
</div>
</div>

@endsection
