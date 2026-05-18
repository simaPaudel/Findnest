<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('payout_wallet_number')->nullable()->after('payout_account_number');
            $table->string('payout_bank_name')->nullable()->after('payout_wallet_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payout_wallet_number',
                'payout_bank_name',
            ]);
        });
    }
};
