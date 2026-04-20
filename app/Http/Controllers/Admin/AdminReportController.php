<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResolveReportRequest;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * List all reports with filtering and sorting.
     */
    public function index(Request $request)
    {
        $query = Report::query()->with(['reporter', 'reportable', 'reviewedByUser']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->byStatus($request->status);
        }

        // Filter by report type
        if ($request->has('type') && $request->type !== '') {
            $query->byType($request->type);
        }

        // Filter by reportable type
        if ($request->has('reportable_type') && $request->reportable_type !== '') {
            $query->for($request->reportable_type);
        }

        // Filter by user relevance
        if ($request->filled('user')) {
            $userId = (int) $request->integer('user');
            $query->where(function ($userQuery) use ($userId) {
                $userQuery->where('reporter_id', $userId)
                    ->orWhere('reviewed_by', $userId)
                    ->orWhere(function ($reportableQuery) use ($userId) {
                        $reportableQuery->where('reportable_type', User::class)
                            ->where('reportable_id', $userId);
                    });
            });
        }

        // Search by reason or reporter name
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reason', 'LIKE', $searchTerm)
                    ->orWhere('additional_info', 'LIKE', $searchTerm)
                    ->orWhereHas('reporter', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', $searchTerm)
                            ->orWhere('email', 'LIKE', $searchTerm);
                    });
            });
        }

        // Sort by creation date (newest first)
        $query->recent();

        $reports = $query->paginate(20)->withQueryString();

        // Get statistics for dashboard
        $stats = [
            'pending' => Report::pending()->count(),
            'under_review' => Report::underReview()->count(),
            'resolved' => Report::resolved()->count(),
            'dismissed' => Report::dismissed()->count(),
            'total' => Report::count(),
        ];

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    /**
     * Show a specific report with details.
     */
    public function show(Report $report)
    {
        $report->load(['reporter', 'reportable', 'reviewedByUser']);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Mark report as under review.
     */
    public function review(Report $report)
    {
        if (!$report->isPending()) {
            return back()->with('error', 'This report is already being processed.');
        }

        try {
            $report->markUnderReview(Auth::id());

            return back()->with('success', 'Report marked as under review.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update report: ' . $e->getMessage());
        }
    }

    /**
     * Show the form to resolve a report.
     */
    public function editResolution(Report $report)
    {
        return view('admin.reports.resolve', compact('report'));
    }

    /**
     * Update report resolution.
     */
    public function updateResolution(ResolveReportRequest $request, Report $report)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            if ($validated['status'] === 'resolved') {
                // Handle resolution logic here (e.g., delete property, disable user, etc)
                $this->handleResolution($report);
            }

            $report->resolve(Auth::id(), $validated['admin_notes'], $validated['status']);

            DB::commit();

            return redirect()
                ->route('admin.reports.show', $report)
                ->with('success', 'Report has been resolved.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->with('error', 'Failed to resolve report: ' . $e->getMessage());
        }
    }

    /**
     * Dismiss a report (not a violation).
     */
    public function dismiss(Report $report)
    {
        if (!in_array($report->status, ['pending', 'under_review'])) {
            return back()->with('error', 'This report cannot be dismissed.');
        }

        try {
            $report->dismiss(Auth::id(), 'Report dismissed by admin.');

            return back()->with('success', 'Report dismissed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to dismiss report: ' . $e->getMessage());
        }
    }

    /**
     * Get report statistics by type.
     */
    public function statistics()
    {
        $reportTypes = Report::groupBy('report_type')
            ->selectRaw('report_type, COUNT(*) as count, COUNT(CASE WHEN status = "pending" THEN 1 END) as pending')
            ->get()
            ->mapWithKeys(function ($item) {
                $report = new Report();
                $label = $report->getReportTypeLabel();

                return [$item->report_type => [
                    'label' => $label,
                    'total' => $item->count,
                    'pending' => $item->pending,
                ]];
            });

        $reportableTypes = Report::groupBy('reportable_type')
            ->selectRaw('reportable_type, COUNT(*) as count, COUNT(CASE WHEN status = "pending" THEN 1 END) as pending')
            ->get()
            ->map(function ($item) {
                $type = $item->reportable_type;
                $label = class_basename($type);

                return [
                    'type' => $type,
                    'label' => $label,
                    'total' => $item->count,
                    'pending' => $item->pending,
                ];
            });

        return view('admin.reports.statistics', compact('reportTypes', 'reportableTypes'));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Handle the resolution action based on report type.
     */
    private function handleResolution(Report $report)
    {
        // This method can be extended to handle specific resolution logic
        // For example: delete property, disable user account, remove review, etc.

        // Currently, just log the resolution
        \Log::info("Report #{$report->id} resolved by admin " . Auth::id());
    }
}
