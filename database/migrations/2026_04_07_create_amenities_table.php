<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the amenities table.
     * 
     * Stores reusable property amenities (WiFi, Parking, Laundry, Kitchen, etc.)
     * These are linked to properties via the property_amenities pivot table.
     * 
     * Relationship: amenities -> property_amenities <- properties
     */
    public function up(): void
    {
        if (!Schema::hasTable('amenities')) {
            Schema::create('amenities', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->text('description')->nullable();
                $table->string('icon', 50)->nullable(); // Icon class or emoji
                $table->timestamps();

                // Index for lookups by name
                $table->index('name');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
