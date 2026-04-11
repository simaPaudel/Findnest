@extends('admin.layout')

@section('title', 'Host Application Review')
@section('page_title', 'Host Application Review')

@section('content')
    @php
        $statusClass = match ($ownerApplication->status) {
            'approved' => 'status-approved',
            'rejected' => 'status-rejected',
            default => 'status-pending',
        };
    @endphp

    <style>
        .application-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .application-panel {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 18px;
        }

        .application-panel h3 {
            margin: 0 0 14px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #111827;
        }

        .application-info-list {
            display: grid;
            gap: 12px;
        }

        .application-info-item {
            display: grid;
            gap: 4px;
        }

        .application-info-item span {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .application-info-item strong {
            color: #111827;
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }

        .application-review-note {
            margin-top: 12px;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 56, 92, 0.12);
            background: rgba(255, 56, 92, 0.04);
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .application-media-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .application-media-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 2px rgba(31, 41, 55, 0.04);
        }

        .application-media-card img {
            width: 100%;
            display: block;
            height: 260px;
            object-fit: contain;
            padding: 8px;
            box-sizing: border-box;
            background: #fafafa;
        }

        .application-media-caption {
            padding: 10px 14px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .application-review-form {
            display: grid;
            gap: 14px;
            padding-top: 6px;
        }

        .application-review-form textarea {
            width: 100%;
            min-height: 120px;
            resize: vertical;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font: inherit;
            color: #111827;
        }

        .application-review-form textarea:focus {
            outline: none;
            border-color: rgba(255, 56, 92, 0.35);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .application-review-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .application-submit-form {
            margin: 0;
        }

        @media (max-width: 860px) {
            .application-detail-grid,
            .application-media-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="admin-dashboard">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>{{ $ownerApplication->full_name }}</h2>
                    <p>{{ $ownerApplication->user->email ?? 'N/A' }} · {{ $ownerApplication->phone }}</p>
                </div>

                <span class="status-pill {{ $statusClass }}">{{ ucfirst($ownerApplication->status) }}</span>
            </div>

            <div style="padding: 20px 22px; display: grid; gap: 20px;">
                <div class="application-detail-grid">
                    <div class="application-panel">
                        <h3>Application Details</h3>
                        <div class="application-info-list">
                            <div class="application-info-item">
                                <span>Full Name</span>
                                <strong>{{ $ownerApplication->full_name }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Phone</span>
                                <strong>{{ $ownerApplication->phone }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Citizenship Number</span>
                                <strong>{{ $ownerApplication->citizenship_number }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Address</span>
                                <strong>{{ $ownerApplication->address }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Submitted At</span>
                                <strong>{{ optional($ownerApplication->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Reviewed At</span>
                                <strong>{{ optional($ownerApplication->reviewed_at)->format('M d, Y h:i A') ?? 'Waiting review' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="application-panel">
                        <h3>Review Notes</h3>
                        <div class="application-info-list">
                            <div class="application-info-item">
                                <span>Status</span>
                                <strong>{{ ucfirst($ownerApplication->status) }}</strong>
                            </div>
                            <div class="application-info-item">
                                <span>Current Note</span>
                                <strong>{{ $ownerApplication->admin_notes ?: 'No admin note added yet.' }}</strong>
                            </div>
                        </div>

                        <div class="application-review-note">
                            Approve to upgrade the linked user account to <strong>owner</strong>. Reject to keep the account as a normal user.
                        </div>
                    </div>
                </div>

                <div class="application-media-grid">
                    <div class="application-media-card">
                        <img src="{{ asset('storage/' . $ownerApplication->citizenship_front) }}" alt="Citizenship front">
                        <div class="application-media-caption">Citizenship Front</div>
                    </div>
                    <div class="application-media-card">
                        <img src="{{ asset('storage/' . $ownerApplication->citizenship_back) }}" alt="Citizenship back">
                        <div class="application-media-caption">Citizenship Back</div>
                    </div>
                </div>

                <div class="application-review-form">
                    <div class="admin-filter-group" style="max-width: 820px;">
                        <label for="admin_notes">Admin Notes</label>
                        <textarea id="admin_notes" placeholder="Add an optional review note">{{ old('admin_notes', $ownerApplication->admin_notes) }}</textarea>
                        <div class="admin-meta-note">Optional. This note is saved with the review decision.</div>
                        @error('admin_notes')
                            <div style="color:#b91c1c; font-size:12px; line-height:1.4; margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="application-review-actions">
                        <form method="POST" action="{{ route('admin.owner-applications.approve', $ownerApplication) }}" class="application-submit-form">
                            @csrf
                            <input type="hidden" name="admin_notes" id="approve_admin_notes" value="{{ old('admin_notes', $ownerApplication->admin_notes) }}">
                            <button type="submit" class="admin-btn admin-btn-success">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('admin.owner-applications.reject', $ownerApplication) }}" class="application-submit-form">
                            @csrf
                            <input type="hidden" name="admin_notes" id="reject_admin_notes" value="{{ old('admin_notes', $ownerApplication->admin_notes) }}">
                            <button type="submit" class="admin-btn admin-btn-danger">Reject</button>
                        </form>

                        <a href="{{ route('admin.owner-applications.index') }}" class="admin-btn admin-btn-secondary">Back to list</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notes = document.getElementById('admin_notes');
            const approveNotes = document.getElementById('approve_admin_notes');
            const rejectNotes = document.getElementById('reject_admin_notes');

            if (!notes || !approveNotes || !rejectNotes) {
                return;
            }

            const syncNotes = () => {
                approveNotes.value = notes.value;
                rejectNotes.value = notes.value;
            };

            notes.addEventListener('input', syncNotes);
            syncNotes();
        });
    </script>
@endsection
