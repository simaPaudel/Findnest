<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('room_name', 100)->nullable();
            $table->string('room_number', 50)->nullable();
            $table->integer('capacity')->default(1);
            $table->integer('current_occupancy')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('availability')->default(true);
            $table->json('room_photos')->nullable();
            $table->text('room_features')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};