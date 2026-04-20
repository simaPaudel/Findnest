<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalProperties = Property::count();
        $pendingProperties = Property::where('status', 'pending')->count();
        $approvedProperties = Property::where('status', 'approved')->count();
        $rejectedProperties = Property::where('status', 'rejected')->count();
        $totalBookings = Booking::count();
        $totalReviews = Review::count();
        $totalRevenue = (float) Payment::paid()->sum('amount');

        $recentProperties = Property::with('owner')
            ->latest()
            ->take(4)
            ->get();

        $recentBookings = Booking::with(['property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        [$activityChart, $bookingChart] = $this->buildDashboardCharts();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalProperties',
            'pendingProperties',
            'approvedProperties',
            'rejectedProperties',
            'totalBookings',
            'totalReviews',
            'totalRevenue',
            'recentProperties',
            'recentBookings',
            'activityChart',
            'bookingChart'
        ));
    }

    /**
     * Build dashboard analytics from live database data.
     */
    private function buildDashboardCharts(): array
    {
        $earliestCreatedAt = collect([
            User::query()->whereIn('role', ['user', 'owner'])->min('created_at'),
            Property::query()->min('created_at'),
            Booking::query()->min('created_at'),
        ])->filter()->sort()->first();

        $chartStart = $earliestCreatedAt
            ? Carbon::parse($earliestCreatedAt)->startOfMonth()
            : now()->startOfMonth();

        $chartEnd = now()->endOfMonth();

        $monthKeys = [];
        $monthLabels = [];

        foreach (CarbonPeriod::create($chartStart, '1 month', now()->startOfMonth()) as $month) {
            $monthKeys[] = $month->format('Y-m');
            $monthLabels[] = $month->format('M');
        }

        $userMonthly = $this->pluckMonthlyCounts(
            User::query()
                ->where('role', 'user')
                ->whereBetween('created_at', [$chartStart, $chartEnd]),
            $monthKeys
        );

        $ownerMonthly = $this->pluckMonthlyCounts(
            User::query()
                ->where('role', 'owner')
                ->whereBetween('created_at', [$chartStart, $chartEnd]),
            $monthKeys
        );

        $propertyMonthly = $this->pluckMonthlyCounts(
            Property::query()
                ->whereBetween('created_at', [$chartStart, $chartEnd]),
            $monthKeys
        );

        $bookingMonthly = $this->pluckMonthlyCounts(
            Booking::query()
                ->whereBetween('created_at', [$chartStart, $chartEnd]),
            $monthKeys
        );

        $activitySeries = [
            [
                'key' => 'users',
                'label' => 'New Users',
                'color' => '#3b82f6',
                'fill' => 'rgba(59, 130, 246, 0.12)',
                'values' => $userMonthly,
            ],
            [
                'key' => 'owners',
                'label' => 'New Owners',
                'color' => '#14b8a6',
                'fill' => 'rgba(20, 184, 166, 0.12)',
                'values' => $ownerMonthly,
            ],
            [
                'key' => 'properties',
                'label' => 'New Properties',
                'color' => '#f59e0b',
                'fill' => 'rgba(245, 158, 11, 0.12)',
                'values' => $propertyMonthly,
            ],
            [
                'key' => 'bookings',
                'label' => 'New Bookings',
                'color' => '#fb7185',
                'fill' => 'rgba(251, 113, 133, 0.12)',
                'values' => $bookingMonthly,
            ],
        ];

        $activityChart = $this->buildLineChart($monthLabels, $activitySeries);

        $bookingTotals = Booking::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $bookingPalette = [
            'confirmed' => ['label' => 'Confirmed', 'color' => '#3b82f6'],
            'pending' => ['label' => 'Pending', 'color' => '#f59e0b'],
            'cancelled' => ['label' => 'Cancelled', 'color' => '#fb7185'],
            'completed' => ['label' => 'Completed', 'color' => '#14b8a6'],
            'rejected' => ['label' => 'Rejected', 'color' => '#ef4444'],
        ];

        $bookingChart = $this->buildDonutChart($bookingTotals, $bookingPalette);

        return [$activityChart, $bookingChart];
    }

    /**
     * Convert monthly database rows into a fixed series aligned to the chart months.
     */
    private function pluckMonthlyCounts($query, array $monthKeys): array
    {
        $rows = $query
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->month => (int) $row->total];
            })
            ->all();

        return array_map(
            fn ($monthKey) => (int) ($rows[$monthKey] ?? 0),
            $monthKeys
        );
    }

    /**
     * Build SVG paths for the live activity chart.
     */
    private function buildLineChart(array $labels, array $series, int $width = 860, int $height = 250, int $padding = 24): array
    {
        $allValues = [];

        foreach ($series as $item) {
            $allValues = array_merge($allValues, $item['values']);
        }

        $maxValue = max(array_merge([1], $allValues));
        $innerWidth = $width - ($padding * 2);
        $innerHeight = $height - ($padding * 2);
        $count = max(count($labels), 1);
        $step = $count > 1 ? $innerWidth / ($count - 1) : 0;

        $processedSeries = [];

        foreach ($series as $item) {
            $points = [];

            foreach ($item['values'] as $index => $value) {
                $x = $count === 1
                    ? $padding + ($innerWidth / 2)
                    : $padding + ($step * $index);
                $y = $padding + $innerHeight - (($value / $maxValue) * $innerHeight);

                $points[] = [
                    'x' => round($x, 1),
                    'y' => round($y, 1),
                ];
            }

            $paths = $this->buildPathStrings($points, $width, $height, $padding);

            $processedSeries[] = array_merge($item, $paths, ['points' => $points]);
        }

        $ticks = [];
        $steps = 4;

        for ($i = $steps; $i >= 0; $i--) {
            $ticks[] = (int) round($maxValue * ($i / $steps));
        }

        return [
            'labels' => $labels,
            'series' => $processedSeries,
            'ticks' => $ticks,
            'width' => $width,
            'height' => $height,
            'padding' => $padding,
        ];
    }

    /**
     * Build SVG line and area strings from a list of points.
     */
    private function buildPathStrings(array $points, int $width, int $height, int $padding): array
    {
        if (empty($points)) {
            return ['line' => '', 'area' => ''];
        }

        if (count($points) === 1) {
            $centerPoint = $points[0];
            $lineStart = $padding;
            $lineEnd = $width - $padding;
            $line = 'M ' . $lineStart . ' ' . $centerPoint['y'] . ' L ' . $lineEnd . ' ' . $centerPoint['y'];
            $area = 'M ' . $lineStart . ' ' . $centerPoint['y'] . ' L ' . $lineEnd . ' ' . $centerPoint['y'] . ' L ' . $lineEnd . ' ' . ($height - $padding) . ' L ' . $lineStart . ' ' . ($height - $padding) . ' Z';

            return [
                'line' => $line,
                'area' => $area,
            ];
        }

        $line = 'M ' . $points[0]['x'] . ' ' . $points[0]['y'];

        for ($i = 1; $i < count($points); $i++) {
            $line .= ' L ' . $points[$i]['x'] . ' ' . $points[$i]['y'];
        }

        $firstPoint = $points[0];
        $lastPoint = $points[count($points) - 1];
        $baseline = $height - $padding;
        $area = $line . ' L ' . $lastPoint['x'] . ' ' . $baseline . ' L ' . $firstPoint['x'] . ' ' . $baseline . ' Z';

        return [
            'line' => $line,
            'area' => $area,
        ];
    }

    /**
     * Build a donut chart description from live counts.
     */
    private function buildDonutChart(array $counts, array $palette): array
    {
        $total = array_sum($counts);
        $segments = [];
        $start = 0.0;

        foreach ($palette as $status => $meta) {
            $count = (int) ($counts[$status] ?? 0);

            if ($count <= 0) {
                continue;
            }

            $slice = $total > 0 ? (($count / $total) * 360) : 0;
            $end = $start + $slice;

            $segments[] = [
                'status' => $status,
                'label' => $meta['label'],
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
                'color' => $meta['color'],
                'start' => $start,
                'end' => $end,
            ];

            $start = $end;
        }

        $background = 'conic-gradient(#e5e7eb 0deg 360deg)';

        if (!empty($segments)) {
            $stops = array_map(function ($segment) {
                return "{$segment['color']} {$segment['start']}deg {$segment['end']}deg";
            }, $segments);

            $background = 'conic-gradient(' . implode(', ', $stops) . ')';
        }

        return [
            'segments' => $segments,
            'background' => $background,
            'total' => $total,
        ];
    }
}
