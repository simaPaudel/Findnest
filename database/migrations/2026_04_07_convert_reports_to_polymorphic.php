<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - convert old reports table to polymorphic structure.
     */
    public function up(): void
    {
        // First, check if the new columns exist, if not add them
        Schema::table('reports', function (Blueprint $table) {
            // Add new polymorphic columns if they don't exist
            if (!Schema::hasColumn('reports', 'reportable_type')) {
                $table->string('reportable_type')->after('reporter_id')->nullable();
            }
            if (!Schema::hasColumn('reports', 'reportable_id')) {
                $table->unsignedBigInteger('reportable_id')->after('reportable_type')->nullable();
            }

            // Add reviewed_by column if it doesn't exist
            if (!Schema::hasColumn('reports', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->after('admin_notes')->nullable();
            }

            // Add missing columns if needed
            if (!Schema::hasColumn('reports', 'reason')) {
                $table->text('reason')->after('report_type')->nullable();
            }

            // Add foreign key for reviewed_by
            if (!Schema::hasColumn('reports', 'reviewed_by')) {
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Migrate data from old structure to new polymorphic structure
        $this->migrateExistingData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['reviewed_by']);

            // Drop the new columns
            $table->dropColumn(['reportable_type', 'reportable_id', 'reviewed_by']);
        });
    }

    /**
     * Migrate existing reports data to new polymorphic structure.
     */
    private function migrateExistingData(): void
    {
        DB::table('reports')
            ->whereNull('reportable_type')
            ->orderBy('id')
            ->chunk(100, function ($reports) {
                foreach ($reports as $report) {
                    $reportableType = null;
                    $reportableId = null;

                    if ($report->property_id) {
                        $reportableType = 'App\\Models\\Property';
                        $reportableId = $report->property_id;
                    } elseif ($report->review_id) {
                        $reportableType = 'App\\Models\\Review';
                        $reportableId = $report->review_id;
                    } elseif ($report->user_id) {
                        $reportableType = 'App\\Models\\User';
                        $reportableId = $report->user_id;
                    } elseif ($report->owner_id) {
                        $reportableType = 'App\\Models\\User';
                        $reportableId = $report->owner_id;
                    }

                    if ($reportableType && $reportableId) {
                        DB::table('reports')
                            ->where('id', $report->id)
                            ->update([
                                'reportable_type' => $reportableType,
                                'reportable_id' => $reportableId,
                            ]);
                    }
                }
            });
    }
};
