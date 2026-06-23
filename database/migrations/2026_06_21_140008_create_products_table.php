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
            $table->string('code')->unique();                           // SKU
            $table->string('barcode')->unique()->nullable();

            // Relations
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('main_category_id')->nullable()->constrained('main_categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');

            // Unit stored as plain strings — no FK to a units table
            $table->string('unit_name')->default('Piece');
            $table->string('unit_code')->default('PCS');

            // Pricing — purchase & selling only; no MRP
            $table->decimal('purchase_price', 15, 2)->default(0.00);
            $table->decimal('selling_price', 15, 2)->default(0.00);
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);

            // Stock alert threshold (actual stock lives in the stocks table)
            $table->decimal('minimum_stock_alert', 15, 2)->default(0.00);

            // Media
            $table->string('image')->nullable();
            $table->text('gallery')->nullable();                        // JSON array of filenames

            // Descriptions
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();

            // Status
            $table->string('status')->default('active');               // active | inactive

            // Optional hardware / product specs
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
