<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payme_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('payme_orders', 'type')) {
                $table->string('type')->default('wallet');
            }
            if (!Schema::hasColumn('payme_orders', 'firebase_collection')) {
                $table->string('firebase_collection')->nullable();
            }
            if (!Schema::hasColumn('payme_orders', 'firebase_order_id')) {
                $table->string('firebase_order_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payme_orders', function (Blueprint $table) {
            $cols = ['type', 'firebase_collection', 'firebase_order_id'];
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('payme_orders', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
