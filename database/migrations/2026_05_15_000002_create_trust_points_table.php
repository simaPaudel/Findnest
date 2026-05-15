<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trust_points')) {
            return;
        }

        Schema::create('trust_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('giver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['booking_id', 'giver_id', 'receiver_id'], 'trust_points_booking_giver_receiver_unique');
            $table->index(['receiver_id', 'created_at'], 'trust_points_receiver_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trust_points');
    }
};
