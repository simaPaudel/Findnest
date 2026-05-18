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

        .content-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .admin-profile-overview {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
            flex-wrap: wrap;
            padding: 24px;
        }

        .admin-profile-overview-main {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            min-width: 0;
            flex: 1 1 560px;
        }

        .admin-profile-avatar {
            width: 156px;
            height: 156px;
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid var(--fn-line);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
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

        .admin-profile-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .admin-profile-summary span {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border: 1px solid #eef2f7;
            border-radius: 999px;
            background: #f9fafb;
            color: #4b5563;
            font-size: 12px;
            font-weight: 650;
            line-height: 1;
        }

        .admin-profile-bio {
            max-width: 680px;
            margin: 12px 0 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
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

        .admin-profile-editor {
            display: none;
        }

        .admin-profile-editor.is-editing {
            display: block;
        }

        .admin-profile-editor .admin-profile-view {
            display: none;
        }

        .admin-profile-editor .admin-profile-form {
            display: none;
        }

        .admin-profile-editor.is-editing .admin-profile-form {
            display: grid;
        }

        .admin-profile-editor.is-editing .admin-profile-view,
        .admin-profile-editor.is-editing [data-profile-edit] {
            display: none;
        }

        .admin-profile-page.is-editing [data-profile-edit] {
            display: none;
        }

        .admin-profile-editor .content-card-header {
            display: none;
        }

        .admin-profile-view {
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .admin-view-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .admin-view-card {
            border: 1px solid var(--fn-line);
            border-radius: 12px;
            background: #fff;
            padding: 16px;
            min-width: 0;
        }

        .admin-view-card.admin-view-card-wide {
            grid-column: 1 / -1;
        }

        .admin-view-label {
            display: block;
            margin-bottom: 6px;
            color: var(--fn-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .admin-view-value {
            margin: 0;
            color: var(--fn-charcoal);
            font-size: 15px;
            line-height: 1.55;
            word-break: break-word;
        }

        .admin-view-note {
            margin: 6px 0 0;
            color: var(--fn-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .admin-profile-grid {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .admin-security-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .admin-password-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.42);
        }

        .admin-password-modal.is-open {
            display: flex;
        }

        .admin-password-dialog {
            width: min(100%, 460px);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            border-radius: 18px;
            background: #fff;
            border: 1px solid var(--fn-line);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
        }

        .admin-password-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 20px 20px 14px;
            border-bottom: 1px solid var(--fn-line);
        }

        .admin-password-header h3 {
            margin: 0;
            color: var(--fn-charcoal);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-password-header p {
            margin: 5px 0 0;
            color: var(--fn-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .admin-password-close {
            width: 34px;
            height: 34px;
            border: 1px solid var(--fn-line);
            border-radius: 999px;
            background: #fff;
            color: #475569;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
        }

        .admin-password-form {
            display: grid;
            gap: 14px;
            padding: 20px;
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

        .password-field {
            position: relative;
        }

        .password-field .admin-form-input {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.18s ease, background 0.18s ease;
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: #ff385c;
            background: rgba(255, 56, 92, 0.06);
            outline: none;
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
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
            .admin-profile-form,
            .admin-profile-view {
                padding: 18px;
            }

            .admin-profile-overview-main {
                flex-direction: column;
            }

            .admin-profile-avatar {
                width: 122px;
                height: 122px;
            }

            .admin-profile-grid {
                grid-template-columns: 1fr;
            }

            .admin-view-grid {
                grid-template-columns: 1fr;
            }

            .admin-security-grid {
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

    <div class="admin-profile-page {{ $errors->any() ? 'is-editing' : '' }}" data-profile-page>
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
                    <div class="admin-profile-summary">
                        <span>{{ $admin->phone ?: 'Phone not set' }}</span>
                        <span>{{ $admin->gender ? ucfirst($admin->gender) : 'Gender not set' }}</span>
                        <span>{{ ucfirst($admin->role) }}</span>
                    </div>
                    @if ($admin->bio)
                        <p class="admin-profile-bio">{{ $admin->bio }}</p>
                    @endif
                </div>
            </div>

            <div class="admin-profile-meta">
                <button type="button" class="admin-btn admin-btn-primary" data-profile-edit>Edit Profile</button>
            </div>
        </section>

        <section class="content-card admin-profile-editor {{ $errors->any() ? 'is-editing' : '' }}" data-profile-editor>
            <div class="content-card-header">
                <div>
                    <h2>Profile Details</h2>
                    <p>Keep your admin account information current and consistent.</p>
                </div>
            </div>

            <div class="admin-profile-view" data-profile-view>
                <div class="admin-view-grid">
                    <div class="admin-view-card">
                        <span class="admin-view-label">Full Name</span>
                        <p class="admin-view-value">{{ $admin->name ?: 'Not set' }}</p>
                    </div>
                    <div class="admin-view-card">
                        <span class="admin-view-label">Email Address</span>
                        <p class="admin-view-value">{{ $admin->email }}</p>
                        <p class="admin-view-note">Email cannot be changed from the profile page.</p>
                    </div>
                    <div class="admin-view-card">
                        <span class="admin-view-label">Phone Number</span>
                        <p class="admin-view-value">{{ $admin->phone ?: 'Not set' }}</p>
                    </div>
                    <div class="admin-view-card">
                        <span class="admin-view-label">Gender</span>
                        <p class="admin-view-value">{{ $admin->gender ? ucfirst($admin->gender) : 'Not set' }}</p>
                    </div>
                    <div class="admin-view-card">
                        <span class="admin-view-label">Role</span>
                        <p class="admin-view-value">{{ ucfirst($admin->role) }}</p>
                    </div>
                    <div class="admin-view-card admin-view-card-wide">
                        <span class="admin-view-label">Bio</span>
                        <p class="admin-view-value">{{ $admin->bio ?: 'No bio added yet.' }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="admin-profile-form" data-profile-form>
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

                                <div class="admin-form-group">
                                    <label for="gender" class="admin-form-label">Gender</label>
                                    <select name="gender" id="gender" class="admin-form-input @error('gender') error @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $admin->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $admin->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $admin->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
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
                    <button type="button" class="admin-btn admin-btn-secondary" data-password-open>Change Password</button>
                    <button type="button" class="admin-btn admin-btn-secondary" data-profile-cancel>Cancel</button>
                    <button type="submit" class="admin-btn admin-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>

        <div class="admin-password-modal {{ session('password_modal_open') ? 'is-open' : '' }}" data-password-modal>
            <div class="admin-password-dialog" role="dialog" aria-modal="true" aria-labelledby="admin-password-title">
                <div class="admin-password-header">
                    <div>
                        <h3 id="admin-password-title">Change Password</h3>
                        <p>Use a strong password with uppercase, lowercase, number, and symbol.</p>
                    </div>
                    <button type="button" class="admin-password-close" aria-label="Close password modal" data-password-close>&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="admin-password-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="profile_action" value="password">

                    <div class="admin-form-group">
                        <label for="password_current" class="admin-form-label">Current Password</label>
                        <div class="password-field">
                            <input type="password" name="current_password" id="password_current" class="admin-form-input @error('current_password') error @enderror" autocomplete="current-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_current" aria-label="Show current password" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="admin-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="password_new" class="admin-form-label">New Password</label>
                        <div class="password-field">
                            <input type="password" name="password" id="password_new" class="admin-form-input @error('password') error @enderror" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_new" aria-label="Show new password" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="admin-form-helper">Minimum 8 characters with uppercase, lowercase, number, and special character.</div>
                        @error('password')
                            <div class="admin-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="password_new_confirmation" class="admin-form-label">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" name="password_confirmation" id="password_new_confirmation" class="admin-form-input" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_new_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="admin-profile-actions">
                        <button type="button" class="admin-btn admin-btn-secondary" data-password-close>Cancel</button>
                        <button type="submit" class="admin-btn admin-btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-profile-page]').forEach((page) => {
                const editor = page.querySelector('[data-profile-editor]');
                const editButtons = page.querySelectorAll('[data-profile-edit]');
                const cancelButton = editor.querySelector('[data-profile-cancel]');
                const form = editor.querySelector('[data-profile-form]');
                const passwordModal = page.querySelector('[data-password-modal]');
                const passwordOpenButtons = page.querySelectorAll('[data-password-open]');
                const passwordCloseButtons = page.querySelectorAll('[data-password-close]');

                editButtons.forEach((editButton) => editButton.addEventListener('click', () => {
                    page.classList.add('is-editing');
                    editor.classList.add('is-editing');
                }));

                cancelButton?.addEventListener('click', () => {
                    form?.reset();
                    page.classList.remove('is-editing');
                    editor.classList.remove('is-editing');
                });

                passwordOpenButtons.forEach((button) => button.addEventListener('click', () => {
                    passwordModal?.classList.add('is-open');
                }));

                passwordCloseButtons.forEach((button) => button.addEventListener('click', () => {
                    passwordModal?.classList.remove('is-open');
                }));

                passwordModal?.addEventListener('click', (event) => {
                    if (event.target === passwordModal) {
                        passwordModal.classList.remove('is-open');
                    }
                });

                page.querySelectorAll('[data-password-toggle]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const input = page.querySelector(`#${button.dataset.passwordToggle}`);
                        if (!input) {
                            return;
                        }

                        const shouldShow = input.type === 'password';
                        input.type = shouldShow ? 'text' : 'password';
                        button.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
                        button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                    });
                });
            });
        });
    </script>
@endsection
