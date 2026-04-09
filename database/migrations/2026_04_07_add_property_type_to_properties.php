<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add property_type column to properties table.
     * 
     * property_type values:
     * - 'house': Full standalone house
     * - 'flat': Apartment or flat
     * - 'room': Single room in a shared space
     * - 'apartment': Multi-room apartment
     * - 'hostel': Hostel-style accommodation
     * - 'other': Other property types
     * 
     * Combined with rental_mode:
     * - rental_mode = 'full_property': Rent entire property
     * - rental_mode = 'rooms': Rent individual rooms
     * - rental_mode = 'hybrid': Can rent both ways
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add property_type column if it doesn't exist
            if (!Schema::hasColumn('properties', 'property_type')) {
                $table->enum('property_type', ['house', 'flat', 'room', 'apartment', 'hostel', 'other'])
                    ->default('flat')
                    ->after('room_type')
                    ->comment('The type of property: house, flat, room, apartment, hostel, or other');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'property_type')) {
                $table->dropColumn('property_type');
            }
        });
    }
};
