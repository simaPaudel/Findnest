<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roommate_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('budget_range', 50)->nullable();
            $table->string('preferred_location')->nullable();
            $table->tinyInteger('cleanliness_level')->nullable();
            $table->enum('sleep_schedule', ['early', 'late', 'flexible'])->nullable();
            $table->enum('study_habits', ['quiet', 'group', 'both'])->nullable();
            $table->enum('gender_preference', ['any', 'male', 'female'])->default('any');
            $table->enum('smoking_preference', ['yes', 'no', 'neutral'])->default('neutral');
            $table->enum('alcohol_preference', ['yes', 'no', 'neutral'])->default('neutral');
            $table->integer('max_roommates')->default(1);
            $table->integer('age_range_min')->nullable();
            $table->integer('age_range_max')->nullable();
            $table->json('interests')->nullable();
            $table->text('additional_preferences')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('roommate_preferences');
    }
};