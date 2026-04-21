@extends('owner.layout')

@section('title', 'Profile')

@section('content')
    @php
        $photoUrl = method_exists($owner, 'profilePhotoUrl') ? $owner->profilePhotoUrl() : null;
        $payoutQrUrl = method_exists($owner, 'payoutQrUrl') ? $owner->payoutQrUrl() : null;
    @endphp

    <style>
        .account-profile-page {
            display: grid;
            gap: 20px;
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
            border: 1px solid var(--fn-line, #e5e7eb);
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
            border: 1px solid var(--fn-line, #e5e7eb);
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
            border: 1px solid var(--fn-line, #e5e7eb);
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

        .account-payout-qr-preview {
            width: min(100%, 190px);
            aspect-ratio: 1 / 1;
            justify-self: start;
        }

        .account-payout-qr-preview img {
            object-fit: contain;
            background: #fff;
            padding: 8px;
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
        .account-form-select,
        .account-form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--fn-line, #e5e7eb);
            border-radius: 10px;
            background: #fff;
            color: var(--fn-charcoal, #1f2937);
            font: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .account-form-input:focus,
        .account-form-select:focus,
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

        .account-form-select {
            appearance: none;
            background: #fff;
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
            border-color: var(--fn-line, #e5e7eb);
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
        }
    </style>

    <div class="account-profile-page">
        <section class="content-card account-profile-overview">
            <div class="account-profile-overview-main">
                <div class="account-profile-avatar">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" alt="{{ $owner->name }}">
                    @else
                        <div class="account-profile-avatar-fallback">
                            {{ strtoupper(substr($owner->name ?? 'O', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="account-profile-heading">
                    <p class="account-profile-kicker">Owner Account</p>
                    <h2>{{ $owner->name }}</h2>
                    <p>{{ $owner->email }}</p>
                </div>
            </div>

            <div class="account-profile-meta">
                <span class="account-status-pill">Owner</span>
                <span class="account-meta-note">Update your name, photo, phone, and bio.</span>
            </div>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Profile Details</h2>
                    <p>Keep your account information current and professional.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data" class="account-profile-form">
                @csrf
                @method('PUT')

                <div class="account-profile-grid">
                    <div class="account-profile-section">
                        <h3>Profile Photo</h3>
                        <p>This image appears in your owner dashboard and profile menu.</p>

                        <div class="account-photo-block">
                            <div class="account-photo-preview">
                                @if ($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $owner->name }}">
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
                                <input type="file" name="profile_photo" id="profile_photo" class="account-form-input @error('profile_photo') error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                                <div class="account-form-helper">Max size: 2MB. Accepted: JPG, PNG, WEBP.</div>
                                @error('profile_photo')
                                    <div class="account-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="account-form-fields">
                        <div class="account-profile-section">
                            <h3>Personal Information</h3>
                            <p>Update the details shown across your owner account.</p>

                            <div style="margin-top: 16px; display: grid; gap: 14px;">
                                <div class="account-form-group">
                                    <label for="name" class="account-form-label">Full Name *</label>
                                    <input type="text" name="name" id="name" class="account-form-input @error('name') error @enderror" value="{{ old('name', $owner->name) }}" required>
                                    @error('name')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="email" class="account-form-label">Email Address</label>
                                    <input type="email" class="account-form-input" value="{{ $owner->email }}" disabled>
                                    <div class="account-form-helper">Email cannot be changed from the profile page.</div>
                                </div>

                                <div class="account-form-group">
                                    <label for="phone" class="account-form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="account-form-input @error('phone') error @enderror" value="{{ old('phone', $owner->phone) }}">
                                    @error('phone')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="account-profile-section">
                            <h3>Bio</h3>
                            <p>A short bio helps renters understand your hosting style.</p>

                            <div class="account-form-group" style="margin-top: 16px;">
                                <label for="bio" class="account-form-label">Bio</label>
                                <textarea name="bio" id="bio" class="account-form-textarea @error('bio') error @enderror" maxlength="1000">{{ old('bio', $owner->bio) }}</textarea>
                                <div class="account-form-helper">Maximum 1000 characters.</div>
                                @error('bio')
                                    <div class="account-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="account-profile-section">
                            <h3>Payout Details</h3>
                            <p>Share the payout route admin should use when sending your earnings.</p>

                            <div style="margin-top: 16px; display: grid; gap: 14px;">
                                <div class="account-form-group">
                                    <label for="payout_method" class="account-form-label">Payout Method</label>
                                    <select name="payout_method" id="payout_method" class="account-form-input account-form-select @error('payout_method') error @enderror">
                                        <option value="">Select method</option>
                                        <option value="khalti" {{ old('payout_method', $owner->payout_method) === 'khalti' ? 'selected' : '' }}>Khalti</option>
                                        <option value="esewa" {{ old('payout_method', $owner->payout_method) === 'esewa' ? 'selected' : '' }}>eSewa</option>
                                        <option value="bank" {{ old('payout_method', $owner->payout_method) === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    @error('payout_method')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="payout_account_name" class="account-form-label">Account Name</label>
                                    <input type="text" name="payout_account_name" id="payout_account_name" class="account-form-input @error('payout_account_name') error @enderror" value="{{ old('payout_account_name', $owner->payout_account_name) }}">
                                    @error('payout_account_name')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="payout_account_number" class="account-form-label">Account Number</label>
                                    <input type="text" name="payout_account_number" id="payout_account_number" class="account-form-input @error('payout_account_number') error @enderror" value="{{ old('payout_account_number', $owner->payout_account_number) }}">
                                    @error('payout_account_number')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="payout_qr" class="account-form-label">QR Upload</label>
                                    <div class="account-photo-preview account-payout-qr-preview">
                                        @if ($payoutQrUrl)
                                            <img src="{{ $payoutQrUrl }}" alt="Payout QR">
                                        @else
                                            <div class="account-photo-placeholder">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5V7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9A2.5 2.5 0 0118.5 19h-13A2.5 2.5 0 013 16.5zM8 12l2.5 2.5L16 9"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="payout_qr" id="payout_qr" class="account-form-input @error('payout_qr') error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                                    <div class="account-form-helper">Optional. JPG, PNG, WEBP. Max size: 2MB.</div>
                                    @error('payout_qr')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="account-form-group">
                                    <label for="payout_notes" class="account-form-label">Notes</label>
                                    <textarea name="payout_notes" id="payout_notes" class="account-form-textarea @error('payout_notes') error @enderror" maxlength="2000">{{ old('payout_notes', $owner->payout_notes) }}</textarea>
                                    <div class="account-form-helper">Optional. Add any payout instructions for the admin.</div>
                                    @error('payout_notes')
                                        <div class="account-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="account-profile-actions">
                    <a href="{{ route('owner.dashboard') }}" class="account-btn account-btn-secondary">Cancel</a>
                    <button type="submit" class="account-btn account-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>
    </div>
@endsection
