<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('payout_method')->nullable()->after('profile_photo');
            $table->string('payout_account_name')->nullable()->after('payout_method');
            $table->string('payout_account_number')->nullable()->after('payout_account_name');
            $table->string('payout_qr')->nullable()->after('payout_account_number');
            $table->text('payout_notes')->nullable()->after('payout_qr');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payout_method',
                'payout_account_name',
                'payout_account_number',
                'payout_qr',
                'payout_notes',
            ]);
        });
    }
};
