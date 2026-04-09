<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add rental_mode column to properties table to support full-property and per-room rentals.
     * 
     * rental_mode values:
     * - 'full_property': Only rent the entire property as one unit with a single rent price
     * - 'per_room': Only rent individual rooms, each with its own price
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('rental_mode', ['full_property', 'per_room'])
                ->default('full_property')
                ->after('room_type')
                ->comment('Determines if property is rented as full unit or by individual rooms');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('rental_mode');
        });
    }
};
