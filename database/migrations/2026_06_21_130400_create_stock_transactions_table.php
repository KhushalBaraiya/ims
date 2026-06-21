<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('quantity');
            $table->unsignedInteger('previous_stock');
            $table->unsignedInteger('current_stock');
            $table->enum('transaction_type', [
                'Opening Stock',
                'Purchase',
                'Purchase Return',
                'Sale',
                'Sale Return',
                'Adjustment',
                'Damage',
            ]);
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
