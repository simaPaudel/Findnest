@extends('user.layout')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
    @php
        $photoUrl = method_exists($user, 'profilePhotoUrl') ? $user->profilePhotoUrl() : null;
    @endphp

    <style>
        .account-profile-page {
            display: grid;
            gap: 20px;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(31, 41, 55, 0.04);
        }

        .content-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .content-card-header h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #1f2937;
        }

        .content-card-header p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .account-profile-overview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            padding: 20px;
        }

        .account-profile-overview-main {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .account-profile-avatar {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .account-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-profile-avatar-fallback {
            width: 100%;
            height: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--fn-red, #ff385c);
            background: rgba(255, 56, 92, 0.08);
        }

        .account-profile-heading {
            min-width: 0;
        }

        .account-profile-kicker {
            margin: 0 0 6px;
            color: var(--fn-red, #ff385c);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .account-profile-heading h2 {
            margin: 0;
            font-size: 22px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .account-profile-heading p {
            margin: 5px 0 0;
            color: var(--fn-gray-dark, #6b7280);
            font-size: 14px;
        }

        .account-profile-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .account-status-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
            line-height: 1;
            border: 1px solid rgba(107, 114, 128, 0.08);
            background: rgba(107, 114, 128, 0.08);
            color: #4b5563;
        }

        .account-meta-note {
            color: var(--fn-gray-dark, #6b7280);
            font-size: 12px;
            line-height: 1.5;
        }

        .account-profile-form {
            padding: 20px;
            display: grid;
            gap: 20px;
        }

        .account-profile-grid {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .account-profile-section {
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            border-radius: 12px;
            background: #fff;
            padding: 18px;
        }

        .account-profile-section h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .account-profile-section p {
            margin: 6px 0 0;
            color: var(--fn-gray-dark, #6b7280);
            font-size: 13px;
            line-height: 1.5;
        }

        .account-photo-block {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }

        .account-photo-preview {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .account-photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 56, 92, 0.06);
            color: var(--fn-red, #ff385c);
        }

        .account-photo-placeholder svg {
            width: 48px;
            height: 48px;
        }

        .account-form-fields {
            display: grid;
            gap: 14px;
        }

        .account-form-group {
            display: grid;
            gap: 8px;
        }

        .account-form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--fn-gray-dark, #6b7280);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .account-form-input,
        .account-form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            border-radius: 10px;
            background: #fff;
            color: var(--fn-charcoal, #1f2937);
            font: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .account-form-input:focus,
        .account-form-textarea:focus {
            outline: none;
            border-color: rgba(255, 56, 92, 0.35);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .account-form-input.error,
        .account-form-textarea.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .account-form-input[disabled] {
            background: #f9fafb;
            color: #6b7280;
        }

        .account-form-textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.5;
        }

        .account-form-helper {
            font-size: 12px;
            color: var(--fn-gray-dark, #6b7280);
            line-height: 1.5;
        }

        .account-form-error {
            color: #b91c1c;
            font-size: 12px;
            line-height: 1.4;
        }

        .account-profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 2px;
        }

        .host-application-panel {
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .host-application-banner {
            display: grid;
            gap: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fafafa;
        }

        .host-application-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .host-application-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1;
            border: 1px solid transparent;
        }

        .host-application-pending {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
            border-color: rgba(245, 158, 11, 0.16);
        }

        .host-application-approved {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
            border-color: rgba(16, 185, 129, 0.16);
        }

        .host-application-rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, 0.16);
        }

        .host-application-neutral {
            background: rgba(107, 114, 128, 0.08);
            color: #4b5563;
            border-color: rgba(107, 114, 128, 0.12);
        }

        .host-application-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .host-application-item {
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
        }

        .host-application-item span {
            display: block;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .host-application-item strong {
            display: block;
            margin-top: 6px;
            color: #111827;
            font-size: 14px;
            line-height: 1.4;
            word-break: break-word;
        }

        .host-application-note {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255, 56, 92, 0.12);
            background: rgba(255, 56, 92, 0.04);
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .host-application-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .account-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: #fff;
            color: var(--fn-charcoal, #1f2937);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .account-btn:hover {
            transform: translateY(-1px);
        }

        .account-btn-primary {
            background: var(--fn-red, #ff385c);
            color: #fff;
            box-shadow: 0 6px 14px rgba(255, 56, 92, 0.12);
        }

        .account-btn-primary:hover {
            background: var(--fn-red-hover, #e11d48);
        }

        .account-btn-secondary {
            background: #fff;
            border-color: var(--fn-gray-border, #e5e7eb);
            color: #475569;
        }

        .account-btn-secondary:hover {
            background: #f8fafc;
            border-color: #dbe4ee;
            color: var(--fn-charcoal, #1f2937);
        }

        @media (max-width: 860px) {
            .account-profile-overview,
            .account-profile-form {
                padding: 18px;
            }

            .account-profile-grid {
                grid-template-columns: 1fr;
            }

            .account-profile-actions {
                justify-content: stretch;
            }

            .account-profile-actions .account-btn {
                width: 100%;
            }

            .host-application-grid {
                grid-template-columns: 1fr;
            }

            .content-card-header {
                padding-left: 18px;
                padding-right: 18px;
            }
        }
    </style>

    <div class="account-profile-page">
        <section class="content-card account-profile-overview">
            <div class="account-profile-overview-main">
                <div class="account-profile-avatar">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" alt="{{ $user->name }}">
                    @else
                        <div class="account-profile-avatar-fallback">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="account-profile-heading">
                    <p class="account-profile-kicker">User Account</p>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            <div class="account-profile-meta">
                <span class="account-status-pill">User</span>
                <span class="account-meta-note">Update your account details and roommate preferences.</span>
            </div>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Profile Details</h2>
                    <p>Keep your account information current and consistent.</p>
                </div>
            </div>

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="account-profile-form">
                @csrf
                @method('PUT')

                <div class="account-profile-grid">
                    <div class="account-profile-section">
                        <h3>Profile Photo</h3>
                        <p>This image appears in your profile menu and on account pages.</p>

                        <div class="account-photo-block">
                            <div class="account-photo-preview">
                                @if ($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $user->name }}">
                                @else
                                    <div class="account-photo-placeholder">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="account-form-group">
                                <label for="profile_photo" class="account-form-label">Update Photo</label>
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/jpg" class="account-form-input @error('profile_photo') error @enderror">
                                <div class="account-form-helper">Max size: 2MB. Accepted: JPG, PNG.</div>
                                @error('profile_photo')
                                    <div class="account-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="account-form-fields">
                        <div class="account-profile-section">
                            <h3>Personal Information</h3>
                            <p>Update the details shown across your user account.</p>

                            <div style="margin-top: 16px; display: grid; gap: 14px;">
                                <div class="account-form-group">
                                    <label for="name" class="account-form-label">Full Name *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="account-form-input @error('name') error @enderror" required>
                                    @error('name')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="email" class="account-form-label">Email Address</label>
                                    <input type="email" id="email" class="account-form-input" value="{{ $user->email }}" disabled>
                                    <div class="account-form-helper">Email cannot be changed from the profile page.</div>
                                </div>

                                <div class="account-form-group">
                                    <label for="phone" class="account-form-label">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="account-form-input @error('phone') error @enderror" placeholder="+977 9812345678">
                                    @error('phone')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="gender" class="account-form-label">Gender</label>
                                    <select id="gender" name="gender" class="account-form-input @error('gender') error @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="account-profile-section">
                            <h3>Bio</h3>
                            <p>Share a short introduction to help with roommate matching.</p>

                            <div class="account-form-group" style="margin-top: 16px;">
                                <label for="bio" class="account-form-label">Tell us about yourself</label>
                                <textarea id="bio" name="bio" class="account-form-textarea @error('bio') error @enderror" placeholder="Share your interests, hobbies, and what you're looking for in roommates...">{{ old('bio', $user->bio) }}</textarea>
                                <div class="account-form-helper">Maximum 500 characters.</div>
                                @error('bio')
                                    <div class="account-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="account-profile-actions">
                    <a href="{{ route('user.dashboard') }}" class="account-btn account-btn-secondary">Cancel</a>
                    <button type="submit" class="account-btn account-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Become a Host</h2>
                    <p>Apply to host properties once your documents are reviewed and approved.</p>
                </div>
            </div>

            <div class="host-application-panel">
                @if ($ownerApplication)
                    <div class="host-application-banner">
                        <div class="host-application-row">
                            <span class="host-application-pill host-application-{{ $ownerApplication->status }}">
                                {{ ucfirst($ownerApplication->status) }}
                            </span>
                            <span class="account-form-helper">
                                Submitted {{ optional($ownerApplication->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                            </span>
                        </div>

                        <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.6;">
                            @if ($ownerApplication->isPending())
                                Your host request is waiting for admin review. You do not need to submit another application.
                            @elseif ($ownerApplication->isApproved())
                                Your host request has been approved. You can use the owner dashboard to manage properties.
                            @else
                                Your previous application was rejected. Please review the note below and submit a new request if needed.
                            @endif
                        </p>

                        @if ($ownerApplication->admin_notes)
                            <div class="host-application-note">
                                <strong style="display:block; color:#111827; margin-bottom:6px;">Admin Note</strong>
                                {{ $ownerApplication->admin_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="host-application-grid">
                        <div class="host-application-item">
                            <span>Full Name</span>
                            <strong>{{ $ownerApplication->full_name }}</strong>
                        </div>
                        <div class="host-application-item">
                            <span>Phone</span>
                            <strong>{{ $ownerApplication->phone }}</strong>
                        </div>
                        <div class="host-application-item">
                            <span>Citizenship Number</span>
                            <strong>{{ $ownerApplication->citizenship_number }}</strong>
                        </div>
                    </div>
                @else
                    <div class="host-application-banner">
                        <div class="host-application-row">
                            <span class="host-application-pill host-application-neutral">No Application</span>
                        </div>
                        <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.6;">
                            You have not submitted a host application yet. Use the button below to open the host application form.
                        </p>
                    </div>
                @endif

                <div class="host-application-actions">
                    <a href="{{ route('user.host-application.show') }}" class="account-btn account-btn-primary">
                        @if ($ownerApplication && $ownerApplication->isRejected())
                            Apply Again
                        @elseif ($ownerApplication && $ownerApplication->isPending())
                            View Status
                        @else
                            Become a Host
                        @endif
                    </a>
                </div>
            </div>
        </section>

    </div>
@endsection
