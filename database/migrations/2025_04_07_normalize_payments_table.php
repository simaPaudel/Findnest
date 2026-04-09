<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all foreign keys on the payments table to see what actually exists
        $foreignKeys = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'payments' 
            AND COLUMN_NAME IN ('user_id', 'student_id', 'property_id')
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Drop foreign keys safely
        Schema::table('payments', function (Blueprint $table) {
            // Try to drop any foreign key on user_id (handles both student_id and user_id)
            try {
                if (Schema::hasColumn('payments', 'user_id')) {
                    DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_user_id_foreign');
                }
            } catch (\Exception $e) {
                // Try alternate name
                try {
                    DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_student_id_foreign');
                } catch (\Exception $e2) {
                    // Key might not exist, continue
                }
            }

            // Try to drop foreign key on property_id
            try {
                if (Schema::hasColumn('payments', 'property_id')) {
                    DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_property_id_foreign');
                }
            } catch (\Exception $e) {
                // Key might not exist, continue
            }
        });

        // Now drop the columns safely
        Schema::table('payments', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('payments', 'user_id')) {
                $columns[] = 'user_id';
            }

            if (Schema::hasColumn('payments', 'student_id')) {
                $columns[] = 'student_id';
            }

            if (Schema::hasColumn('payments', 'property_id')) {
                $columns[] = 'property_id';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Re-add the removed columns
            $table->unsignedBigInteger('user_id')->nullable()->after('booking_id');
            $table->unsignedBigInteger('property_id')->nullable()->after('user_id');

            // Re-add the old foreign keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');
        });
    }
};
