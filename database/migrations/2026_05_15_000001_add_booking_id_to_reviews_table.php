<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'booking_id')) {
                $table->foreignId('booking_id')
                    ->nullable()
                    ->after('property_id')
                    ->constrained('bookings')
                    ->nullOnDelete();
            }
        });

        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropUnique('reviews_user_id_property_id_unique');
            });
        } catch (\Throwable $e) {
            // Older databases may not have this constraint name.
        }

        try {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['booking_id', 'user_id'], 'reviews_booking_user_unique');
            });
        } catch (\Throwable $e) {
            // Keep migration idempotent for academic/local project databases.
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            try {
                $table->dropUnique('reviews_booking_user_unique');
            } catch (\Throwable $e) {
                // Constraint may not exist in every local database.
            }

            if (Schema::hasColumn('reviews', 'booking_id')) {
                $table->dropConstrainedForeignId('booking_id');
            }
        });
    }
};
