<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the property_images table.
     * 
     * Stores gallery images for properties.
     * Similar structure to room_images but for property-level photos.
     * Allows multiple ordered images per property with primary image designation.
     * 
     * Relationship:
     * - property_id -> properties (cascade delete)
     * 
     * When a property is deleted, all its images are automatically deleted.
     */
    public function up(): void
    {
        if (!Schema::hasTable('property_images')) {
            Schema::create('property_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id');
                $table->string('path'); // Storage path or URL
                $table->string('alt_text')->nullable(); // Accessibility text
                $table->integer('order')->default(0); // Display order
                $table->boolean('is_primary')->default(false); // Primary/thumbnail image
                $table->timestamps();

                // Foreign key constraint with cascade delete
                $table->foreign('property_id')
                    ->references('id')
                    ->on('properties')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // Indexes for efficient queries
                $table->index('property_id');
                // Composite index for ordering queries
                $table->index(['property_id', 'order']);
                // Index for finding primary images
                $table->index(['property_id', 'is_primary']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
