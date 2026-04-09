<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show the form to report an item.
     */
    public function create($reportableType, $reportableId)
    {
        // Authorize user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to report content.');
        }

        // Validate the reportable type and get the model
        $reportable = $this->getReportableModel($reportableType, $reportableId);

        if (!$reportable) {
            return back()->with('error', 'Item not found.');
        }

        // Check if user already reported this item
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', $reportableId)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this item.');
        }

        $reportTypes = [
            'inappropriate_content' => 'Inappropriate Content',
            'spam' => 'Spam or Misleading',
            'harassment' => 'Harassment or Bullying',
            'fraud' => 'Fraudulent Activity',
            'fake_listing' => 'Fake Listing',
            'scam' => 'Scam',
            'violence' => 'Violence or Threats',
            'discrimination' => 'Discrimination',
            'copyright' => 'Copyright Infringement',
            'other' => 'Other',
        ];

        return view('report.create', compact('reportable', 'reportableType', 'reportableId', 'reportTypes'));
    }

    /**
     * Store a newly created report.
     */
    public function store(StoreReportRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // Check if user already reported this
            $existingReport = Report::where('reporter_id', $validated['reporter_id'])
                ->where('reportable_type', $validated['reportable_type'])
                ->where('reportable_id', $validated['reportable_id'])
                ->where('status', 'pending')
                ->first();

            if ($existingReport) {
                DB::rollback();
                return back()->with('error', 'You have already reported this item. Please wait for our review.');
            }

            // Create the report
            $report = Report::create($validated);

            DB::commit();

            $adminIds = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->pluck('id');

            foreach ($adminIds as $adminId) {
                try {
                    NotificationService::sendNotification(
                        (int) $adminId,
                        'report',
                        'New report submitted',
                        'A new report has been submitted for review.',
                        route('admin.reports.index')
                    );
                } catch (\Throwable $notificationError) {
                    // Notification failures must not block report submission.
                }
            }

            return back()->with('success', 'Thank you for reporting. Our team will review this shortly.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->with('error', 'Failed to submit report: ' . $e->getMessage());
        }
    }

    /**
     * Show user's own reports.
     */
    public function myReports()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $reports = Report::where('reporter_id', Auth::id())
            ->recent()
            ->paginate(15);

        return view('report.my-reports', compact('reports'));
    }

    /**
     * Show details of a specific report (only if user filed it or is admin).
     */
    public function show(Report $report)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check authorization
        if (!Auth::user()->isAdmin() && $report->reporter_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('report.show', compact('report'));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the reportable model instance.
     */
    private function getReportableModel($reportableType, $reportableId)
    {
        $modelClass = str_replace('\\\\', '\\', $reportableType);

        if ($modelClass === 'App\Models\Property') {
            return Property::find($reportableId);
        } elseif ($modelClass === 'App\Models\Review') {
            return Review::find($reportableId);
        } elseif ($modelClass === 'App\Models\User') {
            return User::find($reportableId);
        }

        return null;
    }
}
