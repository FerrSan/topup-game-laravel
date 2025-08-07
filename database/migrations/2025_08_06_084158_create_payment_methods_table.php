<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // e-wallet, bank_transfer, virtual_account, credit_card
            $table->string('logo')->nullable();
            $table->decimal('fee_flat', 10, 2)->default(0); // biaya tetap
            $table->decimal('fee_percent', 5, 2)->default(0); // biaya persentase
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('instructions')->nullable(); // instruksi pembayaran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};