<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('game_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('payment_method_id')->nullable()->constrained();
            
            // Data pemain
            $table->string('player_id');
            $table->string('server_id')->nullable();
            $table->string('player_name')->nullable();
            $table->string('player_phone')->nullable();
            $table->string('player_email')->nullable();
            
            // Data pembayaran
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_code')->nullable(); // kode unik pembayaran
            $table->string('payment_url')->nullable(); // URL pembayaran (jika ada)
            $table->json('payment_data')->nullable(); // response dari payment gateway
            
            // Status
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'expired', 'refunded'])
                  ->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            // Additional info
            $table->string('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'created_at']);
            $table->index('player_id');
            $table->index('invoice_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};