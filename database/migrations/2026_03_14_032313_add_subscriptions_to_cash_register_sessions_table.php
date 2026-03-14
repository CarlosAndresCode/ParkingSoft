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
        Schema::table('cash_register_sessions', function (Blueprint $table) {
            $table->unsignedInteger('subscriptions_count')->default(0)->after('closed_at');
            $table->decimal('subscriptions_sum', 10, 2)->default(0)->after('subscriptions_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_register_sessions', function (Blueprint $table) {
            $table->dropColumn(['subscriptions_count', 'subscriptions_sum']);
        });
    }
};
