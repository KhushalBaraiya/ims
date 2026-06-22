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
            $table->string('code')->unique(); // SKU
            $table->string('barcode')->unique()->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('main_category_id')->nullable()->constrained('main_categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('mrp', 15, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('opening_stock', 15, 2)->default(0.00);
            $table->decimal('minimum_stock_alert', 15, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->text('gallery')->nullable(); // stored as JSON string / cast to array
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_featured')->default(false);

            // Optional product details
            $table->string('manufacturer')->nullable();
            $table->string('model_number')->nullable();
            $table->string('part_number')->nullable();
            $table->string('warranty')->nullable();
            $table->string('color')->nullable();
            $table->string('weight')->nullable();
            $table->string('country_of_origin')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
