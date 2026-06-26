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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamp('order_date');
            $table->date('delivery_date');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('delivery_address');
            $table->text('greeting_message')->nullable();
            $table->text('customer_note')->nullable();
            $table->text('operator_note')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('payment_status')->default('pending'); // pending, waiting_verification, verified, rejected
            $table->string('order_status')->default('pending_payment'); // pending_payment, waiting_verification, paid, processing, ready, shipped, completed, cancelled, rejected
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
