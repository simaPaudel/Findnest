<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Add comprehensive unique constraints to ensure data integrity.
     * 
     * Unique constraints prevent:
     * - Duplicate records where only one should exist (user preferences, saved listings)
     * - Duplicate amenity assignments
     * - Duplicate amenity names
     * - Duplicate email addresses
     * - Duplicate transaction IDs (payment idempotency)
     * 
     * This migration is safe to run multiple times - checks exist before adding.
     */
    public function up(): void
    {
        // ===== ROOMMATE_PREFERENCES: One per user =====
        Schema::table('roommate_preferences', function (Blueprint $table) {
            // Each user should only have ONE set of preferences
            if (!$this->uniqueConstraintExists('roommate_preferences', 'roommate_preferences_user_id_unique')) {
                $table->unique('user_id', 'roommate_preferences_user_id_unique');
            }
        });

        // ===== PAYMENTS: Transaction uniqueness for idempotency =====
        Schema::table('payments', function (Blueprint $table) {
            // Prevent duplicate payments with same transaction ID
            // Ensures payment gateway webhooks don't create duplicates if called twice
            if (
                Schema::hasColumn('payments', 'transaction_id') &&
                !$this->uniqueConstraintExists('payments', 'payments_transaction_id_unique')
            ) {
                $table->unique('transaction_id', 'payments_transaction_id_unique');
            }
        });

        // ===== USERS: Verification token uniqueness (if column exists) =====
        Schema::table('users', function (Blueprint $table) {
            // If verification_token column exists, make it unique
            // Prevents multiple users from having same token
            if (
                Schema::hasColumn('users', 'verification_token') &&
                !$this->uniqueConstraintExists('users', 'users_verification_token_unique')
            ) {
                $table->unique('verification_token', 'users_verification_token_unique');
            }
        });

        // ===== PROPERTIES: Address uniqueness per owner (optional) =====
        // Note: This is more restrictive - one property per address per owner
        // Only uncomment if you want to prevent duplicate property listings for same address
        /*
        Schema::table('properties', function (Blueprint $table) {
            if (!$this->uniqueConstraintExists('properties', 'properties_owner_address_unique')) {
                $table->unique(['owner_id', 'address'], 'properties_owner_address_unique');
            }
        });
        */

        // ===== VERIFY EXISTING CONSTRAINTS =====
        // These should already exist from earlier migrations, but verify
        $this->verifyUniqueConstraint('users', 'email', 'users_email_unique');
        $this->verifyUniqueConstraint('amenities', 'name', 'amenities_name_unique');
        $this->verifyUniqueConstraint('reviews', ['user_id', 'property_id'], 'reviews_user_id_property_id_unique');
        $this->verifyUniqueConstraint('saved_listings', ['user_id', 'property_id'], 'saved_listings_user_id_property_id_unique');
        $this->verifyUniqueConstraint('property_amenities', ['property_id', 'amenity_id'], 'property_amenities_property_id_amenity_id_unique');
    }

    public function down(): void
    {
        // Safely drop constraints if they exist
        Schema::table('roommate_preferences', function (Blueprint $table) {
            if ($this->uniqueConstraintExists('roommate_preferences', 'roommate_preferences_user_id_unique')) {
                $table->dropUnique('roommate_preferences_user_id_unique');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if ($this->uniqueConstraintExists('payments', 'payments_transaction_id_unique')) {
                $table->dropUnique('payments_transaction_id_unique');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->uniqueConstraintExists('users', 'users_verification_token_unique')) {
                $table->dropUnique('users_verification_token_unique');
            }
        });
    }

    /**
     * Check if a unique constraint exists on a table.
     */
    private function uniqueConstraintExists(string $table, string $constraintName): bool
    {
        try {
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                 WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND TABLE_SCHEMA = DATABASE()",
                [$table, $constraintName]
            );
            return !empty($constraints);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify that a unique constraint exists; add if missing.
     * 
     * Supports both single columns and composite (multi-column) constraints.
     * 
     * @param string $table Table name
     * @param string|array $columns Column(s) to make unique
     * @param string $constraintName Name for the constraint
     */
    private function verifyUniqueConstraint(string $table, $columns, string $constraintName): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        // Normalize columns to array
        $columns = is_array($columns) ? $columns : [$columns];

        // Check all columns exist
        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                return;
            }
        }

        // If constraint already exists, skip
        if ($this->uniqueConstraintExists($table, $constraintName)) {
            return;
        }

        // Try to add the unique constraint
        try {
            Schema::table($table, function (Blueprint $table) use ($columns, $constraintName) {
                if (count($columns) === 1) {
                    $table->unique($columns[0], $constraintName);
                } else {
                    $table->unique($columns, $constraintName);
                }
            });
        } catch (\Exception $e) {
            // Log but don't fail - constraint might exist with different name
            Log::warning("Could not add unique constraint $constraintName on $table: " . $e->getMessage());
        }
    }
};
