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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('payment_code')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', [
                'bank_transfer',
                'credit_card',
                'e_wallet',
                'qris',
                'cash'
            ]);
            $table->enum('status', [
                'pending',
                'processing',
                'success',
                'failed',
                'refunded'
            ])->default('pending');
            $table->string('transaction_id')->nullable(); // ID dari payment gateway
            $table->string('payment_url')->nullable(); // URL pembayaran dari Midtrans
            $table->json('payment_details')->nullable(); // Detail dari payment gateway
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('payment_code');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
