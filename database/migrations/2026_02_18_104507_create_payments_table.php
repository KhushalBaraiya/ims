<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency')->default('INR');
            $table->string('payment_method')->nullable(); // card, upi, netbanking, cod
            $table->string('payment_status')->default('pending'); // pending, success, failed, refunded
            $table->string('transaction_id')->nullable();
            $table->string('gateway')->nullable(); // razorpay, stripe, paypal
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
