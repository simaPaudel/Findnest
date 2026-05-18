@extends('owner.layout')

@section('title', 'Profile')

@section('content')
    @php
        $photoUrl = method_exists($owner, 'profilePhotoUrl') ? $owner->profilePhotoUrl() : null;
        $payoutQrUrl = method_exists($owner, 'payoutQrUrl') ? $owner->payoutQrUrl() : null;
        $payoutEditOpen = session('payout_edit_open') || $errors->getBag('payout')->any();
        $hasPayoutDetails = method_exists($owner, 'hasPayoutDetails')
            ? $owner->hasPayoutDetails()
            : filled($owner->payout_method)
                || filled($owner->payout_account_name)
                || filled($owner->payout_account_number)
                || filled($owner->payout_wallet_number)
                || filled($owner->payout_bank_name)
                || filled($owner->payout_qr)
                || filled($owner->payout_notes);
    @endphp

    <style>
        .account-profile-page {
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

        .account-profile-overview {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
            flex-wrap: wrap;
            padding: 24px;
        }

        .account-profile-overview-main {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            min-width: 0;
            flex: 1 1 560px;
        }

        .account-profile-avatar {
            width: 156px;
            height: 156px;
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid var(--fn-line, #e5e7eb);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
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

        .account-profile-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .account-profile-summary span {
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

        .account-profile-bio {
            max-width: 680px;
            margin: 12px 0 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .account-profile-kicker {
            margin: 0 0 6px;
            color: var(--fn-red, #ff385c);
            font-size: 13px;
            font-weight: 650;
            letter-spacing: 0.01em;
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

        .account-status-pill--configured {
            border-color: rgba(255, 56, 92, 0.16);
            background: rgba(255, 56, 92, 0.08);
            color: var(--fn-red, #ff385c);
        }

        .account-status-pill--empty {
            border-color: #e2e8f0;
            background: #f8fafc;
            color: #64748b;
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

        .account-payout-panel {
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .account-payout-panel .content-card-header {
            align-items: flex-start;
        }

        .account-payout-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .account-payout-view {
            display: grid;
            gap: 14px;
        }

        .account-payout-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .account-payout-summary-card {
            border: 1px solid var(--fn-line, #e5e7eb);
            border-radius: 14px;
            background: #fff;
            padding: 14px;
            min-width: 0;
        }

        .account-payout-summary-card.account-view-card-wide {
            grid-column: 1 / -1;
        }

        .account-payout-summary-label {
            display: block;
            margin-bottom: 6px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .account-payout-summary-value {
            margin: 0;
            color: #111827;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.45;
            word-break: break-word;
        }

        .account-payout-summary-note {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.45;
        }

        .account-payout-preview {
            width: min(100%, 150px);
            aspect-ratio: 1 / 1;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--fn-line, #e5e7eb);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .account-payout-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #fff;
            padding: 8px;
        }

        .account-payout-form {
            display: none;
            gap: 16px;
        }

        .account-payout-panel.is-editing .account-payout-view {
            display: none;
        }

        .account-payout-panel.is-editing .account-payout-form {
            display: grid;
        }

        .account-payout-panel.is-editing [data-payout-edit] {
            display: none;
        }

        .account-payout-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .account-payout-form-grid .account-form-group--wide {
            grid-column: 1 / -1;
        }

        .account-payout-qr-row {
            display: grid;
            grid-template-columns: minmax(0, 150px) minmax(0, 1fr);
            gap: 14px;
            align-items: start;
        }

        .account-payout-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .account-profile-editor {
            display: none;
        }

        .account-profile-editor.is-editing {
            display: block;
        }

        .account-profile-editor .account-profile-view {
            display: none;
        }

        .account-profile-editor .account-profile-form {
            display: none;
        }

        .account-profile-editor.is-editing .account-profile-form {
            display: grid;
        }

        .account-profile-editor.is-editing .account-profile-view,
        .account-profile-editor.is-editing [data-profile-edit] {
            display: none;
        }

        .account-profile-page.is-editing [data-profile-edit] {
            display: none;
        }

        .account-profile-editor .content-card-header {
            display: none;
        }

        .account-profile-view {
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .account-view-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .account-view-card {
            border: 1px solid var(--fn-line, #e5e7eb);
            border-radius: 12px;
            background: #fff;
            padding: 16px;
            min-width: 0;
        }

        .account-view-card.account-view-card-wide {
            grid-column: 1 / -1;
        }

        .account-view-label {
            display: block;
            margin-bottom: 6px;
            color: #6b7280;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .account-view-value {
            margin: 0;
            color: #111827;
            font-size: 15px;
            line-height: 1.55;
            word-break: break-word;
        }

        .account-view-note {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
        }

        .account-profile-grid {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .account-security-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .account-password-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.42);
        }

        .account-password-modal.is-open {
            display: flex;
        }

        .account-password-dialog {
            width: min(100%, 460px);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
        }

        .account-password-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 20px 20px 14px;
            border-bottom: 1px solid #eef2f7;
        }

        .account-password-header h3 {
            margin: 0;
            color: #111827;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .account-password-header p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .account-password-close {
            width: 34px;
            height: 34px;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            background: #fff;
            color: #475569;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
        }

        .account-password-form {
            display: grid;
            gap: 14px;
            padding: 20px;
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

        .password-field {
            position: relative;
        }

        .password-field .account-form-input {
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

        .account-btn-danger {
            background: #fff5f5;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .account-btn-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .account-payout-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        @media (max-width: 860px) {
            .account-profile-overview,
            .account-profile-form,
            .account-profile-view,
            .account-payout-panel {
                padding: 18px;
            }

            .account-profile-overview-main {
                flex-direction: column;
            }

            .account-profile-avatar {
                width: 122px;
                height: 122px;
            }

            .account-profile-grid {
                grid-template-columns: 1fr;
            }

            .account-view-grid {
                grid-template-columns: 1fr;
            }

            .account-payout-grid,
            .account-payout-form-grid,
            .account-payout-qr-row {
                grid-template-columns: 1fr;
            }

            .account-security-grid {
                grid-template-columns: 1fr;
            }

            .account-profile-actions {
                justify-content: stretch;
            }

            .account-profile-actions .account-btn,
            .account-payout-actions .account-btn,
            .account-payout-header-actions .account-btn {
                width: 100%;
            }

            .account-payout-header-actions {
                width: 100%;
                justify-content: stretch;
            }

            .account-payout-header-actions .account-status-pill {
                width: fit-content;
            }

            .account-payout-actions .account-btn {
                width: 100%;
            }
        }
    </style>

    <div class="account-profile-page {{ $errors->any() ? 'is-editing' : '' }}" data-profile-page>
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
                    <div class="account-profile-summary">
                        <span>{{ $owner->phone ?: 'Phone not set' }}</span>
                        <span>{{ $owner->gender ? ucfirst($owner->gender) : 'Gender not set' }}</span>
                        <span>Owner</span>
                    </div>
                    @if ($owner->bio)
                        <p class="account-profile-bio">{{ $owner->bio }}</p>
                    @endif
                </div>
            </div>

            <div class="account-profile-meta">
                <button type="button" class="account-btn account-btn-primary" data-profile-edit>Edit Profile</button>
            </div>
        </section>

        <section class="content-card account-payout-panel {{ $payoutEditOpen ? 'is-editing' : '' }}" data-payout-panel data-payout-reset-url="{{ route('owner.profile.edit') }}">
            <div class="content-card-header">
                <div>
                    <h2>Payout Details</h2>
                    <p>Manage the payout route used for manual owner transfers.</p>
                </div>

                <div class="account-payout-header-actions">
                    <span class="account-status-pill {{ $hasPayoutDetails ? 'account-status-pill--configured' : 'account-status-pill--empty' }}">
                        {{ $hasPayoutDetails ? 'Configured' : 'Not Configured' }}
                    </span>

                    <button type="button" class="account-btn account-btn-primary" data-payout-edit>Edit Payout Details</button>
                </div>
            </div>

            <div class="account-payout-view" data-payout-view>
                <div class="account-payout-grid">
                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Payout Method</span>
                        <p class="account-payout-summary-value">{{ $owner->payout_method ? ucfirst($owner->payout_method) : 'Not set' }}</p>
                    </div>

                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Account Name</span>
                        <p class="account-payout-summary-value">{{ $owner->payout_account_name ?: 'Not set' }}</p>
                    </div>

                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Account Number</span>
                        <p class="account-payout-summary-value">{{ $owner->payout_account_number ?: 'Not set' }}</p>
                    </div>

                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Khalti / eSewa Number</span>
                        <p class="account-payout-summary-value">{{ $owner->payout_wallet_number ?: 'Not set' }}</p>
                    </div>

                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Bank Name</span>
                        <p class="account-payout-summary-value">{{ $owner->payout_bank_name ?: 'Not set' }}</p>
                    </div>

                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Payout QR Code</span>
                        @if ($payoutQrUrl)
                            <div class="account-payout-preview">
                                <img src="{{ $payoutQrUrl }}" alt="Payout QR code for {{ $owner->name }}">
                            </div>
                        @else
                            <p class="account-payout-summary-value">Not set</p>
                        @endif
                    </div>

                <div class="account-payout-summary-card account-view-card-wide">
                    <span class="account-payout-summary-label">Payout Notes</span>
                    <p class="account-payout-summary-value">{{ $owner->payout_notes ?: 'No payout notes added yet.' }}</p>
                    <p class="account-payout-summary-note">Optional transfer instructions for the admin payout flow.</p>
                </div>
                </div>

                @if($hasPayoutDetails)
                    <div class="account-payout-actions">
                        <form method="POST" action="{{ route('owner.profile.update') }}" onsubmit="return confirm('Remove all payout details? This cannot be undone.');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="profile_action" value="payout_clear">
                            <button type="submit" class="account-btn account-btn-danger">Remove payout details</button>
                        </form>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data" class="account-payout-form" data-payout-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="profile_action" value="payout_update">

                <div class="account-payout-form-grid">
                    <div class="account-form-group">
                        <label for="payout_method" class="account-form-label">Payout Method</label>
                        <select name="payout_method" id="payout_method" class="account-form-input account-form-select @error('payout_method', 'payout') error @enderror">
                            <option value="">Select payout method</option>
                            <option value="khalti" {{ old('payout_method', $owner->payout_method) === 'khalti' ? 'selected' : '' }}>Khalti</option>
                            <option value="esewa" {{ old('payout_method', $owner->payout_method) === 'esewa' ? 'selected' : '' }}>eSewa</option>
                            <option value="bank" {{ old('payout_method', $owner->payout_method) === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payout_method', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="payout_account_name" class="account-form-label">Account Name</label>
                        <input type="text" name="payout_account_name" id="payout_account_name" class="account-form-input @error('payout_account_name', 'payout') error @enderror" value="{{ old('payout_account_name', $owner->payout_account_name) }}" placeholder="Name on the wallet or bank account">
                        @error('payout_account_name', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="payout_account_number" class="account-form-label">Account Number</label>
                        <input type="text" name="payout_account_number" id="payout_account_number" class="account-form-input @error('payout_account_number', 'payout') error @enderror" value="{{ old('payout_account_number', $owner->payout_account_number) }}" placeholder="Bank account number">
                        @error('payout_account_number', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="payout_wallet_number" class="account-form-label">Khalti / eSewa Number</label>
                        <input type="text" name="payout_wallet_number" id="payout_wallet_number" class="account-form-input @error('payout_wallet_number', 'payout') error @enderror" value="{{ old('payout_wallet_number', $owner->payout_wallet_number) }}" placeholder="Wallet or mobile number">
                        @error('payout_wallet_number', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="payout_bank_name" class="account-form-label">Bank Name</label>
                        <input type="text" name="payout_bank_name" id="payout_bank_name" class="account-form-input @error('payout_bank_name', 'payout') error @enderror" value="{{ old('payout_bank_name', $owner->payout_bank_name) }}" placeholder="Bank name if using bank transfer">
                        @error('payout_bank_name', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group account-form-group--wide">
                        <label for="payout_notes" class="account-form-label">Payout Notes</label>
                        <textarea name="payout_notes" id="payout_notes" class="account-form-textarea @error('payout_notes', 'payout') error @enderror" maxlength="2000" placeholder="Add any payout instructions or transfer notes...">{{ old('payout_notes', $owner->payout_notes) }}</textarea>
                        <div class="account-form-helper">Optional. Add transfer instructions, reference notes, or owner preferences.</div>
                        @error('payout_notes', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="account-payout-qr-row">
                    <div class="account-payout-summary-card">
                        <span class="account-payout-summary-label">Current QR Preview</span>
                        @if ($payoutQrUrl)
                            <div class="account-payout-preview" style="margin-top: 10px;">
                                <img src="{{ $payoutQrUrl }}" alt="Current payout QR code">
                            </div>
                        @else
                            <p class="account-payout-summary-value">No QR code uploaded yet.</p>
                        @endif
                    </div>

                    <div class="account-form-group">
                        <label for="payout_qr" class="account-form-label">Payout QR Code</label>
                        <input type="file" name="payout_qr" id="payout_qr" class="account-form-input @error('payout_qr', 'payout') error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                        <div class="account-form-helper">Optional. Upload a QR image for wallet-based payout transfers.</div>
                        @error('payout_qr', 'payout')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="account-payout-actions">
                    <button type="button" class="account-btn account-btn-secondary" data-payout-cancel>Cancel</button>
                    <button type="submit" class="account-btn account-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>

        <section class="content-card account-profile-editor {{ $errors->any() ? 'is-editing' : '' }}" data-profile-editor>
            <div class="content-card-header">
                <div>
                    <h2>Profile Details</h2>
                    <p>Keep your account information current and professional.</p>
                </div>
            </div>

            <div class="account-profile-view" data-profile-view>
                <div class="account-view-grid">
                    <div class="account-view-card">
                        <span class="account-view-label">Full Name</span>
                        <p class="account-view-value">{{ $owner->name ?: 'Not set' }}</p>
                    </div>
                    <div class="account-view-card">
                        <span class="account-view-label">Email Address</span>
                        <p class="account-view-value">{{ $owner->email }}</p>
                        <p class="account-view-note">Email cannot be changed from the profile page.</p>
                    </div>
                    <div class="account-view-card">
                        <span class="account-view-label">Phone Number</span>
                        <p class="account-view-value">{{ $owner->phone ?: 'Not set' }}</p>
                    </div>
                    <div class="account-view-card">
                        <span class="account-view-label">Gender</span>
                        <p class="account-view-value">{{ $owner->gender ? ucfirst($owner->gender) : 'Not set' }}</p>
                    </div>
                    <div class="account-view-card account-view-card-wide">
                        <span class="account-view-label">Bio</span>
                        <p class="account-view-value">{{ $owner->bio ?: 'No bio added yet.' }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data" class="account-profile-form" data-profile-form>
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

                                <div class="account-form-group">
                                    <label for="gender" class="account-form-label">Gender</label>
                                    <select name="gender" id="gender" class="account-form-input account-form-select @error('gender') error @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $owner->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $owner->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $owner->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
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

                    </div>
                </div>

                <div class="account-profile-actions">
                    <button type="button" class="account-btn account-btn-secondary" data-password-open>Change Password</button>
                    <button type="button" class="account-btn account-btn-secondary" data-profile-cancel>Cancel</button>
                    <button type="submit" class="account-btn account-btn-primary">Save Changes</button>
                </div>
            </form>
        </section>

        <div class="account-password-modal {{ session('password_modal_open') ? 'is-open' : '' }}" data-password-modal>
            <div class="account-password-dialog" role="dialog" aria-modal="true" aria-labelledby="owner-password-title">
                <div class="account-password-header">
                    <div>
                        <h3 id="owner-password-title">Change Password</h3>
                        <p>Use a strong password with uppercase, lowercase, number, and symbol.</p>
                    </div>
                    <button type="button" class="account-password-close" aria-label="Close password modal" data-password-close>&times;</button>
                </div>

                <form method="POST" action="{{ route('owner.profile.update') }}" class="account-password-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="profile_action" value="password">

                    <div class="account-form-group">
                        <label for="password_current" class="account-form-label">Current Password</label>
                        <div class="password-field">
                            <input type="password" name="current_password" id="password_current" class="account-form-input @error('current_password') error @enderror" autocomplete="current-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_current" aria-label="Show current password" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="password_new" class="account-form-label">New Password</label>
                        <div class="password-field">
                            <input type="password" name="password" id="password_new" class="account-form-input @error('password') error @enderror" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_new" aria-label="Show new password" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="account-form-helper">Minimum 8 characters with uppercase, lowercase, number, and special character.</div>
                        @error('password')
                            <div class="account-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-form-group">
                        <label for="password_new_confirmation" class="account-form-label">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" name="password_confirmation" id="password_new_confirmation" class="account-form-input" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-password-toggle="password_new_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="account-profile-actions">
                        <button type="button" class="account-btn account-btn-secondary" data-password-close>Cancel</button>
                        <button type="submit" class="account-btn account-btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-profile-page]').forEach((page) => {
                const editor = page.querySelector('[data-profile-editor]');
                const payoutPanel = page.querySelector('[data-payout-panel]');
                const editButtons = page.querySelectorAll('[data-profile-edit]');
                const cancelButton = editor.querySelector('[data-profile-cancel]');
                const form = editor.querySelector('[data-profile-form]');
                const payoutEditButtons = page.querySelectorAll('[data-payout-edit]');
                const payoutCancelButton = page.querySelector('[data-payout-cancel]');
                const payoutForm = page.querySelector('[data-payout-form]');
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

                payoutEditButtons.forEach((editButton) => editButton.addEventListener('click', () => {
                    payoutPanel?.classList.add('is-editing');
                }));

                payoutCancelButton?.addEventListener('click', () => {
                    const resetUrl = payoutPanel?.dataset.payoutResetUrl;
                    if (resetUrl) {
                        window.location.href = resetUrl;
                        return;
                    }

                    payoutForm?.reset();
                    payoutPanel?.classList.remove('is-editing');
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
