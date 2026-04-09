<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - refactor bookings for hybrid rental platform.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop payment_status column (payments handled separately in payments table)
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            // Drop duration_months (calculated from check_in and check_out dates)
            if (Schema::hasColumn('bookings', 'duration_months')) {
                $table->dropColumn('duration_months');
            }

            // Add rejected_at for tracking when bookings are rejected
            if (!Schema::hasColumn('bookings', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('cancelled_at');
            }

            // Add rejection_reason for tracking why bookings were rejected
            if (!Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }

            // Ensure room_id is nullable (hybrid bookings support)
            if (Schema::hasColumn('bookings', 'room_id')) {
                // Already set to nullable in original migration
            }

            // Add proper indexes for performance
            if (Schema::hasColumn('bookings', 'property_id')) {
                try {
                    $table->index('property_id');
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }

            if (Schema::hasColumn('bookings', 'user_id')) {
                try {
                    $table->index('user_id');
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }

            if (Schema::hasColumn('bookings', 'status')) {
                try {
                    $table->index('status');
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }

            // Index for room bookings
            if (Schema::hasColumn('bookings', 'room_id')) {
                try {
                    $table->index('room_id');
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }

            // Index for date range queries
            if (Schema::hasColumn('bookings', 'check_in_date')) {
                try {
                    $table->index(['check_in_date', 'check_out_date']);
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Restore payment_status
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])
                ->default('unpaid')
                ->after('total_rent');

            // Restore duration_months
            $table->integer('duration_months')
                ->nullable()
                ->after('check_out_date');

            // Remove new columns
            $table->dropColumn(['rejected_at', 'rejection_reason']);

            // Drop indexes
            $table->dropIndex(['property_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['room_id']);
            $table->dropIndex(['check_in_date', 'check_out_date']);
        });
    }
};
