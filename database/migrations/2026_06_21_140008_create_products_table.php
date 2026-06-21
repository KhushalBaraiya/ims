<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('code')->unique();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('main_category_id')->nullable()->constrained('main_categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('sale_price', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('tax_type')->default('exclude'); // exclude, include
            $table->decimal('stock_alert_qty', 15, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
