<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('sub_total', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('shipping_amount', 15, 2)->default(0.00);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('due_amount', 15, 2)->default(0.00);
            $table->string('payment_status', 50)->default('Unpaid'); // Unpaid, Partial, Paid
            $table->string('payment_method')->nullable();
            $table->string('discount_type', 10)->default('fixed'); // fixed, percentage
            $table->decimal('discount_value', 15, 2)->default(0.00); // raw input value
            $table->decimal('tax_percentage', 5, 2)->default(0.00); // global tax %
            $table->text('notes')->nullable();
            $table->string('status')->default('Completed'); // Completed, Pending, Draft, Repair, Ordered
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Created by
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
