<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->enum('review_type', ['property', 'roommate'])->default('property');
            $table->tinyInteger('rating');
            $table->text('review_text')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->integer('helpful_votes')->default(0);
            $table->integer('trust_points_awarded')->default(0);
            $table->timestamps();

            // Prevent duplicate reviews for same property by same user
            $table->unique(['student_id', 'property_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};