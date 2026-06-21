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

            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->nullable();

            // Relationships
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('sub_in_categories_id');

            // Description
            $table->string('description', 1000)->nullable();
            $table->longText('product_details')->nullable();

            // Pricing
            $table->decimal('original_price', 10, 2)->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->unsignedTinyInteger('discount_percent')->default(0);

            // Media
            $table->json('images')->nullable();

            // Extra Info
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('quantity')->default(0);

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_favourite')->default(false);

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEYS
            |--------------------------------------------------------------------------
            */
            $table->foreign('category_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('sub_in_categories_id')->references('id')->on('sub_in_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};