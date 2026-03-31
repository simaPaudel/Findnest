<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roommate_preferences', function (Blueprint $table) {
            // Fix cleanliness_level from tinyInteger to enum string
            $table->enum('cleanliness_level', ['very_clean', 'clean', 'moderate', 'relaxed'])->nullable()->change();
            
            // Fix sleep_schedule enum values
            $table->enum('sleep_schedule', ['early_bird', 'night_owl', 'flexible'])->nullable()->change();
            
            // Fix study_habits enum values
            $table->enum('study_habits', ['quiet', 'moderate', 'social'])->nullable()->change();
            
            // Fix smoking_preference enum values
            $table->enum('smoking_preference', ['yes', 'no', 'outside_only'])->default('no')->change();
            
            // Fix alcohol_preference enum values
            $table->enum('alcohol_preference', ['yes', 'no', 'occasionally'])->default('no')->change();
            
            // Fix interests from json to string (for comma-separated values)
            $table->string('interests', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roommate_preferences', function (Blueprint $table) {
            // Revert cleanliness_level
            $table->tinyInteger('cleanliness_level')->nullable()->change();
            
            // Revert sleep_schedule
            $table->enum('sleep_schedule', ['early', 'late', 'flexible'])->nullable()->change();
            
            // Revert study_habits
            $table->enum('study_habits', ['quiet', 'group', 'both'])->nullable()->change();
            
            // Revert smoking_preference
            $table->enum('smoking_preference', ['yes', 'no', 'neutral'])->default('neutral')->change();
            
            // Revert alcohol_preference
            $table->enum('alcohol_preference', ['yes', 'no', 'neutral'])->default('neutral')->change();
            
            // Revert interests
            $table->json('interests')->nullable()->change();
        });
    }
};
