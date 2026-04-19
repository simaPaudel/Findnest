<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
        }

        .page {
            padding: 28px 32px;
        }

        .header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 18px;
            margin-bottom: 24px;
        }

        .brand {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #64748b;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        .muted {
            color: #64748b;
        }

        .meta {
            margin-top: 8px;
            font-size: 11px;
        }

        .status {
            margin-top: 16px;
            padding: 12px 14px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 10px;
        }

        .section {
            margin-top: 22px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .box {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .row {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .row:last-child {
            border-bottom: none;
        }

        .row-table {
            width: 100%;
            border-collapse: collapse;
        }

        .row-table td:last-child {
            text-align: right;
            font-weight: 700;
        }

        .grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
            margin: -12px;
        }

        .grid-card {
            width: 50%;
            vertical-align: top;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .footer-note {
            margin-top: 22px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #475569;
        }
    </style>
</head>
<body>
@php
    $bookedItemLabel = $booking->isRoomSpecific() && $booking->room
        ? $booking->room->room_name
        : 'Entire property';
    $totalPaid = (float) $booking->getTotalPaid();
    $remainingBalance = max((float) $booking->total_rent - $totalPaid, 0);
    $lastPayment = $booking->lastSuccessfulPayment();
@endphp
<div class="page">
    <div class="header">
        <div class="brand"><x-findnest-logo variant="inline" size="sm" /></div>
        <h1>Monthly Rental Invoice</h1>
        <div class="meta muted">
            Reference #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} | Issued {{ $booking->created_at->format('M d, Y') }}
        </div>
        <div class="status">
            <strong>{{ $booking->isRoomSpecific() ? 'Room booked' : 'Property booked' }}</strong><br>
            Advance payment has been received successfully. Remaining balance: @npr($remainingBalance)
        </div>
    </div>

    <div class="section">
        <div class="section-title">Booking Summary</div>
        <table class="grid" role="presentation">
            <tr>
                <td class="grid-card">
                    <div class="label">Property</div>
                    <div class="value">{{ $booking->property->title }}</div>
                </td>
                <td class="grid-card">
                    <div class="label">Booked Unit</div>
                    <div class="value">{{ $bookedItemLabel }}</div>
                </td>
            </tr>
            <tr>
                <td class="grid-card">
                    <div class="label">Location</div>
                    <div class="value">
                        {{ $booking->property->address ?: ($booking->property->location ?: $booking->property->city) }}
                        @if($booking->property->city), {{ $booking->property->city }}@endif
                    </div>
                </td>
                <td class="grid-card">
                    <div class="label">Move-in Date</div>
                    <div class="value">{{ $booking->check_in_date->format('M d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Invoice Summary</div>
        <div class="box">
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>One month rent</td>
                        <td>@npr($booking->total_rent)</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Advance required now (20%)</td>
                        <td>@npr($dueNow)</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Advance received</td>
                        <td>@npr($totalPaid)</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Remaining balance</td>
                        <td>@npr($remainingBalance)</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Payment Record</div>
        <div class="box">
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Payment method</td>
                        <td>{{ $lastPayment?->getMethodLabel() ?? 'Khalti' }}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Paid on</td>
                        <td>{{ $lastPayment?->paid_at?->format('M d, Y') ?? $booking->updated_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="row-table" role="presentation">
                    <tr>
                        <td>Transaction ID</td>
                        <td>{{ $lastPayment?->transaction_id ?: 'Recorded' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="footer-note">
        This invoice confirms the first-month rent details, the advance payment collected, and the remaining amount left on the booking.
    </div>
</div>
</body>
</html>
