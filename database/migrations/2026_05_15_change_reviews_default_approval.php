<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Change review system: reviews are now auto-approved (shown immediately)
     * Admin can hide improper reviews instead of pre-approving them.
     */
    public function up(): void
    {
        // Update existing reviews with is_approved = false to true
        DB::table('reviews')
            ->where('is_approved', false)
            ->update(['is_approved' => true]);

        // Change the column default for new reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true)->change();
        });
    }

    public function down(): void
    {
        // Revert existing reviews to not approved
        DB::table('reviews')
            ->where('is_approved', true)
            ->update(['is_approved' => false]);

        // Change the column default back
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->change();
        });
    }
};
