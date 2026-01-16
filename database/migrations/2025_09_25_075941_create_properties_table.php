<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            $table->string('landmark')->nullable();
            $table->decimal('rent_price', 10, 2);
            $table->enum('room_type', ['single', 'shared', 'flat']);
            $table->enum('gender_preference', ['any', 'male', 'female'])->default('any');
            $table->boolean('furnished')->default(false);
            $table->integer('total_rooms')->default(1);
            $table->json('amenities')->nullable();
            $table->json('photos')->nullable();
            $table->text('rules')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('properties');
    }
};