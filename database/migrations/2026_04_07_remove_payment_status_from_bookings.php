<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove payment_status from bookings table
     * 
     * Payment status should be determined by querying the payments relationship.
     * This removes redundancy and ensures single source of truth.
     * 
     * Booking statuses represent reservation state:
     * - pending: created but not paid
     * - confirmed: payment successful and booking is active
     * - cancelled: booking was cancelled
     * - completed: booking period completed
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Remove payment_status column if it exists
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Restore payment_status column for rollback
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])
                ->default('unpaid')
                ->after('total_rent');
        });
    }
};
