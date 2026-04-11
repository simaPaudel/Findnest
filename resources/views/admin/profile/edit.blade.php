@extends('admin.layout')

@section('title', 'Profile')
@section('page_kicker', 'Account')
@section('page_title', 'Profile')

@section('content')
    <style>
        .admin-profile-page {
            display: grid;
            gap: 20px;
        }

        .admin-profile-overview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            padding: 20px;
        }

        .admin-profile-overview-main {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .admin-profile-avatar {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--fn-line);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .admin-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-profile-avatar-fallback {
            width: 100%;
            height: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--fn-red);
            background: rgba(255, 56, 92, 0.08);
        }

        .admin-profile-heading {
            min-width: 0;
        }

        .admin-profile-heading h2 {
            margin: 0;
            font-size: 22px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .admin-profile-heading p {
            margin: 5px 0 0;
            color: var(--fn-muted);
            font-size: 14px;
        }

        .admin-profile-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-profile-form {
            padding: 20px;
            display: grid;
            gap: 20px;
        }

        .admin-profile-grid {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .admin-profile-section {
            border: 1px solid var(--fn-line);
            border-radius: 12px;
            background: #fff;
            padding: 18px;
        }

        .admin-profile-section h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-profile-section p {
            margin: 6px 0 0;
            color: var(--fn-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .admin-photo-block {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }

        .admin-photo-preview {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--fn-line);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 56, 92, 0.06);
            color: var(--fn-red);
        }

        .admin-photo-placeholder svg {
            width: 48px;
            height: 48px;
        }

        .admin-profile-fields {
            display: grid;
            gap: 14px;
        }

        .admin-form-group {
            display: grid;
            gap: 8px;
        }

        .admin-form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--fn-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .admin-form-input,
        .admin-form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--fn-line);
            border-radius: 10px;
            background: #fff;
            color: var(--fn-charcoal);
            font: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .admin-form-input:focus,
        .admin-form-textarea:focus {
            outline: none;
            border-color: rgba(255, 56, 92, 0.35);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .admin-form-input.error,
        .admin-form-textarea.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .admin-form-input[disabled] {
            background: #f9fafb;
            color: #6b7280;
        }

        .admin-form-textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.5;
        }

        .admin-form-helper {
            font-size: 12px;
            color: var(--fn-muted);
            line-height: 1.5;
        }

        .admin-form-error {
            color: #b91c1c;
            font-size: 12px;
            line-height: 1.4;
        }

        .admin-profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 2px;
        }

        @media (max-width: 860px) {
            .admin-profile-overview,
            .admin-profile-form {
                padding: 18px;
            }

            .admin-profile-grid {
                grid-template-columns: 1fr;
            }

            .admin-profile-actions {
                justify-content: stretch;
            }

            .admin-profile-actions .admin-btn {
                width: 100%;
            }
        }
    </style>

    <div class="admin-profile-page">
        <section class="content-card admin-profile-overview">
            <div class="admin-profile-overview-main">
                <div class="admin-profile-avatar">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" alt="{{ $admin->name }}">
                    @else
                        <div class="admin-profile-avatar-fallback">
                            {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="admin-profile-heading">
                    <p class="page-kicker" style="margin-bottom: 6px;">Administrator Account</p>
                    <h2>{{ $admin->name }}</h2>
                    <p>{{ $admin->email }}</p>
                </div>
            </div>

            <div class="admin-profile-meta">
                <span class="status-pill status-neutral">{{ ucfirst($admin->role) }}</span>
                <span class="admin-meta-note">Update your name, photo, phone, and bio.</span>
            </div>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Profile Details</h2>
                    <p>Keep your admin account information current and consistent.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="admin-profile-form">
                @csrf
                @method('PUT')

                <div class="admin-profile-grid">
                    <div class="admin-profile-section">
                        <h3>Profile Photo</h3>
                        <p>This photo appears in the admin panel and account menu.</p>

                        <div class="admin-photo-block">
                            <div class="admin-photo-preview">
                                @if ($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $admin->name }}">
                                @else
                                    <div class="admin-photo-placeholder">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="admin-form-group">
                                <label for="profile_photo" class="admin-form-label">Update Photo</label>
                                <input type="file" name="profile_photo" id="profile_photo" class="admin-form-input @error('profile_photo') error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                                <div class="admin-form-helper">Max size: 2MB. Accepted: JPG, PNG, WEBP.</div>
                                @error('profile_photo')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-profile-fields">
                        <div class="admin-profile-section">
                            <h3>Personal Information</h3>
                            <p>Use the same details across your admin account and notifications.</p>

                            <div style="margin-top: 16px; display: grid; gap: 14px;">
                                <div class="admin-form-group">
                                    <label for="name" class="admin-form-label">Full Name *</label>
                                    <input type="text" name="name" id="name" class="admin-form-input @error('name') error @enderror" value="{{ old('name', $admin->name) }}" required>
                                    @error('name')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="admin-form-group">
                                    <label for="email" class="admin-form-label">Email Address</label>
                                    <input type="email" class="admin-form-input" value="{{ $admin->email }}" disabled>
                                    <div class="admin-form-helper">Email cannot be changed from the profile page.</div>
                                </div>

                                <div class="admin-form-group">
                                    <label for="phone" class="admin-form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="admin-form-input @error('phone') error @enderror" value="{{ old('phone', $admin->phone) }}">
                                    @error('phone')
                                        <div class="admin-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="admin-profile-section">
                            <h3>Bio</h3>
                            <p>A short description helps keep your account profile clear and professional.</p>

                            <div class="admin-form-group" style="margin-top: 16px;">
                                <label for="bio" class="admin-form-label">Bio</label>
                                <textarea name="bio" id="bio" class="admin-form-textarea @error('bio') error @enderror" maxlength="1000">{{ old('bio', $admin->bio) }}</textarea>
                                <div class="admin-form-helper">Maximum 1000 characters.</div>
                                @error('bio')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="admin-profile-actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">Cancel</a>
                    <button type="submit" class="admin-btn admin-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>
    </div>
@endsection
