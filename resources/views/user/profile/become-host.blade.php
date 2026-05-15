@extends('user.layout')

@section('title', 'Become a Host')
@section('page-title', 'Become a Host')

@section('content')
    @php
        $status = $application?->status;
        $statusLabel = match ($status) {
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'No Application',
        };
        $statusClass = match ($status) {
            'pending' => 'host-status-pending',
            'approved' => 'host-status-approved',
            'rejected' => 'host-status-rejected',
            default => 'host-status-neutral',
        };
        $canShowForm = ! $application || $application->isRejected();
        $canEditPending = $application?->isPending();
    @endphp

    <style>
        .host-application-page {
            display: grid;
            gap: 20px;
        }

        .host-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(31, 41, 55, 0.04);
        }

        .host-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .host-card-header h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #1f2937;
        }

        .host-card-header p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .host-card-body {
            padding: 20px;
            display: grid;
            gap: 18px;
        }

        .host-alert {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: 14px;
            line-height: 1.5;
        }

        .host-alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.22);
            color: #047857;
        }

        .host-alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.22);
            color: #991b1b;
        }

        .host-status-banner {
            display: grid;
            gap: 18px;
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fafafa;
        }

        .host-status-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .host-status-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .host-edit-toggle {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .host-edit-toggle:hover {
            border-color: #fb7185;
            color: #fb7185;
        }

        .host-status-pill {
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

        .host-status-pending {
            background: rgba(245, 158, 11, 0.14);
            border-color: rgba(245, 158, 11, 0.16);
            color: #b45309;
        }

        .host-status-approved {
            background: rgba(16, 185, 129, 0.12);
            border-color: rgba(16, 185, 129, 0.16);
            color: #047857;
        }

        .host-status-rejected {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.16);
            color: #b91c1c;
        }

        .host-status-neutral {
            background: rgba(107, 114, 128, 0.08);
            border-color: rgba(107, 114, 128, 0.12);
            color: #4b5563;
        }

        .host-status-title {
            margin: 10px 0 0;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #111827;
        }

        .host-status-copy {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .host-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .host-summary-item {
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
        }

        .host-summary-item span {
            display: block;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .host-summary-item strong {
            display: block;
            margin-top: 6px;
            color: #111827;
            font-size: 14px;
            line-height: 1.4;
            word-break: break-word;
        }

        .host-doc-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .host-edit-panel {
            display: grid;
            gap: 16px;
            padding: 18px;
            border: 1px dashed #e5e7eb;
            border-radius: 16px;
            background: #fcfcfd;
        }

        .host-edit-panel-header h4 {
            margin: 0;
            color: #111827;
            font-size: 15px;
            font-weight: 800;
        }

        .host-edit-panel-header p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .host-doc-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(31, 41, 55, 0.04);
        }

        .host-upload-preview {
            display: grid;
            gap: 10px;
            margin-top: 8px;
        }

        .host-upload-preview-shell {
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fafafa;
            overflow: hidden;
        }

        .host-upload-preview-shell img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            display: block;
            padding: 8px;
            box-sizing: border-box;
        }

        .host-upload-preview-empty {
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .host-upload-preview-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 6px;
        }

        .host-upload-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: rgba(15, 23, 42, 0.72);
            color: #fff;
            cursor: pointer;
            transition: transform 0.18s ease, background 0.18s ease, opacity 0.18s ease;
        }

        .host-upload-icon-btn:hover {
            transform: translateY(-1px);
            background: rgba(15, 23, 42, 0.9);
        }

        .host-upload-icon-btn svg {
            width: 14px;
            height: 14px;
        }

        .host-upload-file-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .host-upload-file-row .host-form-input {
            flex: 1;
            min-width: 220px;
        }

        .host-upload-mini-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            width: 32px;
            padding: 0;
            border-radius: 9px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
            transition: border-color 0.18s ease, color 0.18s ease, background 0.18s ease;
        }

        .host-upload-mini-btn svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }

        .host-upload-mini-btn:hover {
            border-color: rgba(255, 56, 92, 0.28);
            color: #e11d48;
            background: #fff7f8;
        }

        .host-doc-card img {
            width: 100%;
            height: 260px;
            object-fit: contain;
            display: block;
            padding: 8px;
            box-sizing: border-box;
            background: #fafafa;
        }

        .host-doc-caption {
            padding: 10px 14px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .host-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .host-form-group {
            display: grid;
            gap: 8px;
        }

        .host-form-group-full {
            grid-column: 1 / -1;
        }

        .host-form-label {
            font-size: 12px;
            font-weight: 800;
            color: #6b7280;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .host-form-input,
        .host-form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            color: #111827;
            font: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .host-form-input:focus,
        .host-form-textarea:focus {
            outline: none;
            border-color: rgba(255, 56, 92, 0.35);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .host-form-input.error,
        .host-form-textarea.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .host-form-textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.5;
        }

        .host-form-helper {
            font-size: 12px;
            line-height: 1.5;
            color: #6b7280;
        }

        .host-form-error {
            color: #b91c1c;
            font-size: 12px;
            line-height: 1.4;
        }

        .host-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .host-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1px solid transparent;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .host-btn:hover {
            transform: translateY(-1px);
        }

        .host-btn-primary {
            background: #ff385c;
            color: #fff;
            box-shadow: 0 6px 14px rgba(255, 56, 92, 0.12);
        }

        .host-btn-primary:hover {
            background: #e11d48;
        }

        .host-btn-secondary {
            background: #fff;
            border-color: #e5e7eb;
            color: #475569;
        }

        .host-btn-secondary:hover {
            background: #f8fafc;
            border-color: #dbe4ee;
            color: #111827;
        }

        .host-empty-state {
            padding: 18px;
            border: 1px dashed #e5e7eb;
            border-radius: 12px;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            background: #fafafa;
        }

        @media (max-width: 860px) {
            .host-card-header,
            .host-card-body {
                padding: 18px;
            }

            .host-summary-grid,
            .host-doc-grid,
            .host-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="host-application-page">
        @if ($errors->any())
            <div class="host-alert host-alert-error">
                <strong style="display:block; margin-bottom:6px;">Please fix the following issues:</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="host-card">
            <div class="host-card-header">
                        <div>
                            <h2>Host Application Status</h2>
                            <p>Apply once, wait for review, and update your documents while the request is pending.</p>
                        </div>

                @if ($application)
                    <span class="host-status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                @endif
            </div>

            <div class="host-card-body">
                @if ($application)
                    <div class="host-status-banner">
                        <div class="host-status-row">
                            <div>
                                <span class="host-status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                                <h3 class="host-status-title">
                                    @if ($application->isPending())
                                        Your application is under review.
                                    @elseif ($application->isApproved())
                                        Your application has been approved.
                                    @else
                                        Your previous application was rejected.
                                    @endif
                                </h3>
                                <p class="host-status-copy">
                                    @if ($application->isPending())
                                        The admin team will review your documents shortly. You do not need to submit another request.
                                    @elseif ($application->isApproved())
                                        You can now access owner features with your approved host account.
                                    @else
                                        You can review the admin note below and submit a new application if you want to apply again.
                                    @endif
                                </p>
                            </div>

                            <div class="host-status-meta">
                                <span style="color:#6b7280; font-size:12px; line-height:1.5;">
                                    Submitted {{ optional($application->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                                </span>
                                @if ($canEditPending)
                                    <button type="button" class="host-edit-toggle" data-host-edit-toggle>Edit</button>
                                @endif
                            </div>
                        </div>

                        <div class="host-summary-grid">
                            <div class="host-summary-item">
                                <span>Full Name</span>
                                <strong>{{ $application->full_name }}</strong>
                            </div>
                            <div class="host-summary-item">
                                <span>Phone</span>
                                <strong>{{ $application->phone }}</strong>
                            </div>
                            <div class="host-summary-item">
                                <span>Citizenship Number</span>
                                <strong>{{ $application->citizenship_number }}</strong>
                            </div>
                            <div class="host-summary-item">
                                <span>Address</span>
                                <strong>{{ $application->address }}</strong>
                            </div>
                            <div class="host-summary-item">
                                <span>Submitted At</span>
                                <strong>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}</strong>
                            </div>
                            <div class="host-summary-item">
                                <span>Reviewed At</span>
                                <strong>{{ optional($application->reviewed_at)->format('M d, Y h:i A') ?? 'Waiting review' }}</strong>
                            </div>
                        </div>

                        <div class="host-doc-grid">
                            <div class="host-doc-card">
                                <img src="{{ asset('storage/' . $application->citizenship_front) }}" alt="Citizenship front">
                                <div class="host-doc-caption">Citizenship Front</div>
                            </div>
                            <div class="host-doc-card">
                                <img src="{{ asset('storage/' . $application->citizenship_back) }}" alt="Citizenship back">
                                <div class="host-doc-caption">Citizenship Back</div>
                            </div>
                        </div>

                        @if ($canEditPending)
                            <div class="host-edit-panel" data-host-edit-panel hidden>
                                <div class="host-edit-panel-header">
                                    <h4>Update Documents</h4>
                                    <p>Replace only the front and back citizenship images for the current review.</p>
                                </div>

                                <form method="POST" action="{{ route('user.host-application.store') }}" enctype="multipart/form-data" class="host-card-body" style="padding:0; gap:16px;">
                                    @csrf

                                    <input type="hidden" name="full_name" value="{{ old('full_name', $application->full_name) }}">
                                    <input type="hidden" name="phone" value="{{ old('phone', $application->phone) }}">
                                    <input type="hidden" name="citizenship_number" value="{{ old('citizenship_number', $application->citizenship_number) }}">
                                    <input type="hidden" name="address" value="{{ old('address', $application->address) }}">

                                    <div class="host-form-grid">
                                        <div class="host-form-group host-form-group-full">
                                            <label for="citizenship_front_edit" class="host-form-label">Citizenship Front Image</label>
                                            <div class="host-upload-file-row">
                                                <input
                                                    type="file"
                                                    id="citizenship_front_edit"
                                                    name="citizenship_front"
                                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                                    class="host-form-input @error('citizenship_front') error @enderror"
                                                >
                                                <button type="button" class="host-upload-mini-btn" data-upload-trigger="citizenship_front_edit" aria-label="Change front image">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="host-upload-mini-btn" data-upload-clear="citizenship_front_edit" aria-label="Delete front image">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-1 0l-.857 12.004A2 2 0 0114.146 21H9.854a2 2 0 01-1.997-1.996L7 7m3 4v6m4-6v6M9 7V4h6v3"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="host-form-helper">Replace the current front-side image only.</div>
                                            <div class="host-upload-preview" data-upload-preview="citizenship_front_edit" hidden>
                                                <div class="host-upload-preview-shell">
                                                    <img alt="Citizenship front preview">
                                                    <div class="host-upload-preview-actions">
                                                        <button type="button" class="host-upload-icon-btn" data-upload-trigger="citizenship_front_edit" aria-label="Edit front image">
                                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('citizenship_front')
                                                <div class="host-form-error">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="host-form-group host-form-group-full">
                                            <label for="citizenship_back_edit" class="host-form-label">Citizenship Back Image</label>
                                            <div class="host-upload-file-row">
                                                <input
                                                    type="file"
                                                    id="citizenship_back_edit"
                                                    name="citizenship_back"
                                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                                    class="host-form-input @error('citizenship_back') error @enderror"
                                                >
                                                <button type="button" class="host-upload-mini-btn" data-upload-trigger="citizenship_back_edit" aria-label="Change back image">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="host-upload-mini-btn" data-upload-clear="citizenship_back_edit" aria-label="Delete back image">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-1 0l-.857 12.004A2 2 0 0114.146 21H9.854a2 2 0 01-1.997-1.996L7 7m3 4v6m4-6v6M9 7V4h6v3"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="host-form-helper">Replace the current back-side image only.</div>
                                            <div class="host-upload-preview" data-upload-preview="citizenship_back_edit" hidden>
                                                <div class="host-upload-preview-shell">
                                                    <img alt="Citizenship back preview">
                                                    <div class="host-upload-preview-actions">
                                                        <button type="button" class="host-upload-icon-btn" data-upload-trigger="citizenship_back_edit" aria-label="Edit back image">
                                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('citizenship_back')
                                                <div class="host-form-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="host-form-actions" style="margin-top: 2px;">
                                        <button type="button" class="host-btn host-btn-secondary" data-host-edit-cancel>Cancel</button>
                                        <button type="submit" class="host-btn host-btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if ($application->admin_notes)
                            <div class="host-empty-state">
                                <strong style="display:block; color:#111827; margin-bottom:6px;">Admin Note</strong>
                                {{ $application->admin_notes }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="host-empty-state">
                        No host application has been submitted yet. Fill the form below to apply as a property host.
                    </div>
                @endif
            </div>
        </section>

        @if ($canShowForm)
            <section class="host-card">
                <div class="host-card-header">
                    <div>
                        <h2>Become a Host</h2>
                        <p>Submit your identity details for admin review. You can reapply after a rejection.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.host-application.store') }}" enctype="multipart/form-data" class="host-card-body">
                    @csrf

                    <div class="host-form-grid">
                        <div class="host-form-group">
                            <label for="full_name" class="host-form-label">Full Name</label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                value="{{ old('full_name', $application?->full_name ?? $user->name) }}"
                                class="host-form-input @error('full_name') error @enderror"
                                required
                            >
                            @error('full_name')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="host-form-group">
                            <label for="phone" class="host-form-label">Phone</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $application?->phone ?? $user->phone) }}"
                                class="host-form-input @error('phone') error @enderror"
                                required
                            >
                            @error('phone')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="host-form-group">
                            <label for="citizenship_number" class="host-form-label">Citizenship Number</label>
                            <input
                                type="text"
                                id="citizenship_number"
                                name="citizenship_number"
                                value="{{ old('citizenship_number', $application?->citizenship_number) }}"
                                class="host-form-input @error('citizenship_number') error @enderror"
                                required
                            >
                            @error('citizenship_number')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="host-form-group host-form-group-full">
                            <label for="address" class="host-form-label">Address</label>
                            <textarea
                                id="address"
                                name="address"
                                class="host-form-textarea @error('address') error @enderror"
                                placeholder="Enter your complete address for verification"
                                required
                            >{{ old('address', $application?->address) }}</textarea>
                            <div class="host-form-helper">Use the address shown on your citizenship or current residence.</div>
                            @error('address')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="host-form-group host-form-group-full">
                            <label for="citizenship_front" class="host-form-label">Citizenship Front Image</label>
                            <div class="host-upload-file-row">
                                <input
                                    type="file"
                                    id="citizenship_front"
                                    name="citizenship_front"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="host-form-input @error('citizenship_front') error @enderror"
                                    required
                                >
                                <button type="button" class="host-upload-mini-btn" data-upload-trigger="citizenship_front" aria-label="Change front image">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                    </svg>
                                </button>
                                <button type="button" class="host-upload-mini-btn" data-upload-clear="citizenship_front" aria-label="Delete front image">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-1 0l-.857 12.004A2 2 0 0114.146 21H9.854a2 2 0 01-1.997-1.996L7 7m3 4v6m4-6v6M9 7V4h6v3"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="host-form-helper">Upload a clear front-side scan or photo.</div>
                            <div class="host-upload-preview" data-upload-preview="citizenship_front" hidden>
                                <div class="host-upload-preview-shell">
                                    <img alt="Citizenship front preview">
                                    <div class="host-upload-preview-actions">
                                        <button type="button" class="host-upload-icon-btn" data-upload-trigger="citizenship_front" aria-label="Edit front image">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('citizenship_front')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="host-form-group host-form-group-full">
                            <label for="citizenship_back" class="host-form-label">Citizenship Back Image</label>
                            <div class="host-upload-file-row">
                                <input
                                    type="file"
                                    id="citizenship_back"
                                    name="citizenship_back"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="host-form-input @error('citizenship_back') error @enderror"
                                    required
                                >
                                <button type="button" class="host-upload-mini-btn" data-upload-trigger="citizenship_back" aria-label="Change back image">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                    </svg>
                                </button>
                                <button type="button" class="host-upload-mini-btn" data-upload-clear="citizenship_back" aria-label="Delete back image">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-1 0l-.857 12.004A2 2 0 0114.146 21H9.854a2 2 0 01-1.997-1.996L7 7m3 4v6m4-6v6M9 7V4h6v3"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="host-form-helper">Upload a clear back-side scan or photo.</div>
                            <div class="host-upload-preview" data-upload-preview="citizenship_back" hidden>
                                <div class="host-upload-preview-shell">
                                    <img alt="Citizenship back preview">
                                    <div class="host-upload-preview-actions">
                                        <button type="button" class="host-upload-icon-btn" data-upload-trigger="citizenship_back" aria-label="Edit back image">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6a2 2 0 012 2v6m-1.293-7.707l-9.5 9.5a1 1 0 00-.263.47l-1 4a1 1 0 001.207 1.207l4-1a1 1 0 00.47-.263l9.5-9.5m-14.914 4.914l5.5 5.5"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('citizenship_back')
                                <div class="host-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="host-form-actions" style="margin-top: 2px;">
                        <a href="{{ route('user.profile.edit') }}" class="host-btn host-btn-secondary">Back to Profile</a>
                        <button type="submit" class="host-btn host-btn-primary">Submit Application</button>
                    </div>
                </form>
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editToggle = document.querySelector('[data-host-edit-toggle]');
            const editPanel = document.querySelector('[data-host-edit-panel]');
            const editCancel = document.querySelector('[data-host-edit-cancel]');

            if (editToggle && editPanel) {
                editToggle.addEventListener('click', function () {
                    editPanel.hidden = !editPanel.hidden;
                    if (!editPanel.hidden) {
                        editPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }

            if (editCancel && editPanel) {
                editCancel.addEventListener('click', function () {
                    editPanel.hidden = true;
                });
            }

            const wireUpload = (inputId) => {
                const input = document.getElementById(inputId);
                const preview = document.querySelector('[data-upload-preview="' + inputId + '"]');
                const previewImage = preview ? preview.querySelector('img') : null;
                const triggerButtons = document.querySelectorAll('[data-upload-trigger="' + inputId + '"]');
                const clearButtons = document.querySelectorAll('[data-upload-clear="' + inputId + '"]');

                if (!input || !preview || !previewImage) {
                    return;
                }

                const updatePreview = () => {
                    const file = input.files && input.files[0] ? input.files[0] : null;

                    if (!file) {
                        preview.hidden = true;
                        previewImage.removeAttribute('src');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    previewImage.src = objectUrl;
                    preview.hidden = false;
                };

                triggerButtons.forEach((button) => {
                    button.addEventListener('click', function () {
                        input.click();
                    });
                });

                clearButtons.forEach((button) => {
                    button.addEventListener('click', function () {
                        input.value = '';
                        preview.hidden = true;
                        previewImage.removeAttribute('src');
                    });
                });

                input.addEventListener('change', updatePreview);
                updatePreview();
            };

            wireUpload('citizenship_front');
            wireUpload('citizenship_back');
            wireUpload('citizenship_front_edit');
            wireUpload('citizenship_back_edit');
        });
    </script>
@endsection
