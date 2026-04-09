<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the property_amenities pivot table.
     * 
     * Many-to-many relationship between properties and amenities.
     * Allows each property to have multiple amenities and each amenity to be used on multiple properties.
     * 
     * Relationships:
     * - property_id -> properties (cascade delete)
     * - amenity_id -> amenities (cascade delete)
     */
    public function up(): void
    {
        if (!Schema::hasTable('property_amenities')) {
            Schema::create('property_amenities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id');
                $table->unsignedBigInteger('amenity_id');
                $table->timestamps();

                // Foreign key constraints - use cascade for both since amenities are shared
                $table->foreign('property_id')
                    ->references('id')
                    ->on('properties')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('amenity_id')
                    ->references('id')
                    ->on('amenities')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // Prevent duplicate entries
                $table->unique(['property_id', 'amenity_id']);

                // Indexes for efficient queries
                $table->index('property_id');
                $table->index('amenity_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('property_amenities');
    }
};
