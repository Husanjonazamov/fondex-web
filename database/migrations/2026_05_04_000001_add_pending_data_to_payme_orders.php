<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payme_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('payme_orders', 'pending_order_data')) {
                $table->json('pending_order_data')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payme_orders', function (Blueprint $table) {
            if (Schema::hasColumn('payme_orders', 'pending_order_data')) {
                $table->dropColumn('pending_order_data');
            }
        });
    }
};
