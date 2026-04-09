<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add comprehensive indexes using raw SQL for maximum compatibility.
     * Only creates indexes for tables and columns that actually exist.
     * Uses IF NOT EXISTS to be idempotent - safe to run multiple times.
     */
    public function up(): void
    {
        // Disable foreign key checks to avoid issues with add indexes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // ===== USERS TABLE =====
            $this->addIndexIfColumnsExist('users', 'idx_users_role', ['role']);
            $this->addIndexIfColumnsExist('users', 'idx_users_is_verified', ['is_verified']);
            $this->addIndexIfColumnsExist('users', 'idx_users_role_is_verified', ['role', 'is_verified']);

            // ===== PROPERTIES TABLE =====
            $this->addIndexIfColumnsExist('properties', 'idx_properties_owner_id', ['owner_id']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_status', ['status']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_city', ['city']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_room_type', ['room_type']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_status_city', ['status', 'city']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_status_room_type', ['status', 'room_type']);
            $this->addIndexIfColumnsExist('properties', 'idx_properties_owner_status', ['owner_id', 'status']);

            // ===== ROOMS TABLE =====
            $this->addIndexIfColumnsExist('rooms', 'idx_rooms_property_id', ['property_id']);
            $this->addIndexIfColumnsExist('rooms', 'idx_rooms_availability', ['availability']);
            $this->addIndexIfColumnsExist('rooms', 'idx_rooms_property_availability', ['property_id', 'availability']);

            // ===== BOOKINGS TABLE =====
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_property_id', ['property_id']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_room_id', ['room_id']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_status', ['status']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_check_in_date', ['check_in_date']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_check_out_date', ['check_out_date']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_dates', ['check_in_date', 'check_out_date']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_user_status', ['user_id', 'status']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_property_status', ['property_id', 'status']);
            $this->addIndexIfColumnsExist('bookings', 'idx_bookings_conflict', ['property_id', 'check_in_date', 'check_out_date', 'status']);

            // ===== PAYMENTS TABLE =====
            $this->addIndexIfColumnsExist('payments', 'idx_payments_booking_id', ['booking_id']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_property_id', ['property_id']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_payment_status', ['payment_status']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_payment_method', ['payment_method']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_payment_type', ['payment_type']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_transaction_id', ['transaction_id']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_user_status', ['user_id', 'payment_status']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_booking_status', ['booking_id', 'payment_status']);
            $this->addIndexIfColumnsExist('payments', 'idx_payments_paid_at', ['paid_at']);

            // ===== REVIEWS TABLE =====
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_property_id', ['property_id']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_is_approved', ['is_approved']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_review_type', ['review_type']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_rating', ['rating']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_property_approved', ['property_id', 'is_approved']);
            $this->addIndexIfColumnsExist('reviews', 'idx_reviews_property_rating_approved', ['property_id', 'rating', 'is_approved']);

            // ===== REPORTS TABLE =====
            $this->addIndexIfColumnsExist('reports', 'idx_reports_reporter_id', ['reporter_id']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_property_id', ['property_id']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_owner_id', ['owner_id']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_review_id', ['review_id']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_report_type', ['report_type']);
            $this->addIndexIfColumnsExist('reports', 'idx_reports_status', ['status']);

            // ===== ROOMMATE_PREFERENCES TABLE =====
            $this->addIndexIfColumnsExist('roommate_preferences', 'idx_roommate_preferences_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('roommate_preferences', 'idx_roommate_preferences_gender', ['gender_preference']);
            $this->addIndexIfColumnsExist('roommate_preferences', 'idx_roommate_preferences_smoking', ['smoking_preference']);
            $this->addIndexIfColumnsExist('roommate_preferences', 'idx_roommate_preferences_alcohol', ['alcohol_preference']);

            // ===== SAVED_LISTINGS TABLE =====
            $this->addIndexIfColumnsExist('saved_listings', 'idx_saved_listings_user_id', ['user_id']);
            $this->addIndexIfColumnsExist('saved_listings', 'idx_saved_listings_property_id', ['property_id']);

            // ===== ROOM_IMAGES TABLE =====
            if ($this->tableExists('room_images')) {
                $this->addIndexIfColumnsExist('room_images', 'idx_room_images_room_id', ['room_id']);
                $this->addIndexIfColumnsExist('room_images', 'idx_room_images_order', ['room_id', 'order']);
            }

            // ===== PROPERTY_IMAGES TABLE =====
            if ($this->tableExists('property_images')) {
                $this->addIndexIfColumnsExist('property_images', 'idx_property_images_property_id', ['property_id']);
                $this->addIndexIfColumnsExist('property_images', 'idx_property_images_order', ['property_id', 'order']);
                // Also try with display_order if it exists instead
                if (!$this->columnExists('property_images', 'order') && $this->columnExists('property_images', 'display_order')) {
                    $this->addIndexIfColumnsExist('property_images', 'idx_property_images_order', ['property_id', 'display_order']);
                }
            }

            // ===== PROPERTY_AMENITIES TABLE =====
            if ($this->tableExists('property_amenities')) {
                $this->addIndexIfColumnsExist('property_amenities', 'idx_property_amenities_property', ['property_id']);
                $this->addIndexIfColumnsExist('property_amenities', 'idx_property_amenities_amenity', ['amenity_id']);
            }

            // ===== AMENITIES TABLE =====
            if ($this->tableExists('amenities')) {
                $this->addIndexIfColumnsExist('amenities', 'idx_amenities_name', ['name']);
            }

            // ===== APP_NOTIFICATIONS TABLE =====
            if ($this->tableExists('app_notifications')) {
                $this->addIndexIfColumnsExist('app_notifications', 'idx_app_notifications_user_id', ['user_id']);
                $this->addIndexIfColumnsExist('app_notifications', 'idx_app_notifications_is_read', ['is_read']);
                $this->addIndexIfColumnsExist('app_notifications', 'idx_app_notifications_user_read', ['user_id', 'is_read']);
                $this->addIndexIfColumnsExist('app_notifications', 'idx_app_notifications_created_at', ['created_at']);
            }
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        // Dropping indexes is not typically necessary in rollback
        // If needed, you can manually drop them, but leaving them is harmless
        // They just provide extra query performance optimization
    }

    /**
     * Add an index only if all specified columns exist in the table.
     */
    private function addIndexIfColumnsExist(string $table, string $indexName, array $columns): void
    {
        if (!$this->tableExists($table)) {
            return;
        }

        // Check if all columns exist
        foreach ($columns as $column) {
            if (!$this->columnExists($table, $column)) {
                return;
            }
        }

        // All columns exist - add the index
        $columnList = implode('`, `', $columns);
        DB::statement("ALTER TABLE `{$table}` ADD INDEX IF NOT EXISTS `{$indexName}` (`{$columnList}`)");
    }

    /**
     * Check if a table exists.
     */
    private function tableExists(string $table): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a column exists in a table.
     */
    private function columnExists(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }
};
