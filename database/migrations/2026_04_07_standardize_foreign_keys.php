<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Standardize foreign key column names and ensure type consistency.
     * 
     * This migration standardizes:
     * 1. Rename student_id -> user_id for consistency across all tables
     * 2. Ensures all foreign key columns use unsigned big integer (default for ->foreignId())
     * 3. Verifies foreign key constraints are properly defined
     * 
     * NOTE: Run AFTER backup, as this involves column renames.
     * If you want to skip this and keep student_id, comment out the renames below.
     */
    public function up(): void
    {
        // ===== BOOKINGS TABLE: Rename student_id -> user_id =====
        if (Schema::hasColumn('bookings', 'student_id') && !Schema::hasColumn('bookings', 'user_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Drop the old foreign key constraint first
                try {
                    $table->dropForeign('bookings_student_id_foreign');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }

                // Rename the column
                $table->renameColumn('student_id', 'user_id');

                // Re-add the foreign key with new column name
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        // ===== PAYMENTS TABLE: Rename student_id -> user_id =====
        if (Schema::hasColumn('payments', 'student_id') && !Schema::hasColumn('payments', 'user_id')) {
            Schema::table('payments', function (Blueprint $table) {
                // Drop the old foreign key constraint first
                try {
                    $table->dropForeign('payments_student_id_foreign');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }

                // Rename the column
                $table->renameColumn('student_id', 'user_id');

                // Re-add the foreign key with new column name
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        // ===== REVIEWS TABLE: Rename student_id -> user_id =====
        if (Schema::hasColumn('reviews', 'student_id') && !Schema::hasColumn('reviews', 'user_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                // Drop the old foreign key constraint and unique constraint
                try {
                    $table->dropUnique('reviews_student_id_property_id_unique');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }

                try {
                    $table->dropForeign('reviews_student_id_foreign');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }

                // Rename the column
                $table->renameColumn('student_id', 'user_id');

                // Re-add constraints with new column name
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->unique(['user_id', 'property_id']);
            });
        }

        // ===== ROOMMATE_PREFERENCES TABLE: Rename student_id -> user_id =====
        if (Schema::hasColumn('roommate_preferences', 'student_id') && !Schema::hasColumn('roommate_preferences', 'user_id')) {
            Schema::table('roommate_preferences', function (Blueprint $table) {
                // Drop the old foreign key constraint first
                try {
                    $table->dropForeign('roommate_preferences_student_id_foreign');
                } catch (\Exception $e) {
                    // Constraint might not exist
                }

                // Rename the column
                $table->renameColumn('student_id', 'user_id');

                // Re-add the foreign key with new column name
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        // ===== Verify all foreign keys are present and correct =====
        // This is a safeguard for tables that already have correct names

        $this->verifyAndAddForeignKey('bookings', 'user_id', 'users');
        $this->verifyAndAddForeignKey('bookings', 'property_id', 'properties');
        $this->verifyAndAddForeignKey('bookings', 'room_id', 'rooms');

        $this->verifyAndAddForeignKey('payments', 'booking_id', 'bookings');
        $this->verifyAndAddForeignKey('payments', 'user_id', 'users');
        $this->verifyAndAddForeignKey('payments', 'property_id', 'properties');

        $this->verifyAndAddForeignKey('reviews', 'user_id', 'users');
        $this->verifyAndAddForeignKey('reviews', 'property_id', 'properties');

        $this->verifyAndAddForeignKey('roommate_preferences', 'user_id', 'users');

        $this->verifyAndAddForeignKey('saved_listings', 'user_id', 'users');
        $this->verifyAndAddForeignKey('saved_listings', 'property_id', 'properties');

        $this->verifyAndAddForeignKey('reports', 'reporter_id', 'users');
        $this->verifyAndAddForeignKey('reports', 'property_id', 'properties');
        $this->verifyAndAddForeignKey('reports', 'owner_id', 'users');
        $this->verifyAndAddForeignKey('reports', 'review_id', 'reviews');
        $this->verifyAndAddForeignKey('reports', 'user_id', 'users');

        $this->verifyAndAddForeignKey('room_images', 'room_id', 'rooms');
    }

    public function down(): void
    {
        // This is a risky migration to reverse as renames could lose data
        // Manual intervention recommended for rollback
    }

    /**
     * Helper to verify foreign key exists, or add it if missing.
     */
    private function verifyAndAddForeignKey(string $table, string $column, string $referencedTable): void
    {
        if (!Schema::hasColumn($table, $column)) {
            return; // Column doesn't exist, skip
        }

        // Check if foreign key already exists
        try {
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                 WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$table, $column]
            );

            if (!empty($constraints)) {
                return; // Foreign key already exists
            }
        } catch (\Exception $e) {
            // Could not check, proceed anyway
        }

        // Add the foreign key if it doesn't exist
        try {
            Schema::table($table, function (Blueprint $table) use ($column, $referencedTable) {
                $table->foreign($column)
                    ->references('id')
                    ->on($referencedTable)
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist or other issue, skip silently
            Log::warning("Could not add foreign key $table.$column -> $referencedTable: " . $e->getMessage());
        }
    }
};
