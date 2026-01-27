<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('payme_orders', function (Blueprint $table) {
            $table->id();

            // kimning walleti to‘ldiriladi
            $table->unsignedBigInteger('user_id')->nullable();

            // Payme amount (TIYINDA!)
            $table->bigInteger('amount');

            // 0 = yangi, 1 = to‘langan, 2 = bekor qilingan
            $table->tinyInteger('state')->default(0);

            // hozir faqat wallet, keyin order ham qo‘shish mumkin
            $table->string('type')->default('wallet');

            $table->timestamps();

            // optional: agar users jadvali bo‘lsa
            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payme_orders');
    }
};
