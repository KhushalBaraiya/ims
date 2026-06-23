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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            }

            if (Schema::hasColumn('products', 'currency_id')) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            }

            if (!Schema::hasColumn('products', 'unit_name')) {
                $table->string('unit_name')->nullable()->after('sub_category_id');
            }
            if (!Schema::hasColumn('products', 'unit_code')) {
                $table->string('unit_code')->nullable()->after('unit_name');
            }
            if (!Schema::hasColumn('products', 'base_unit')) {
                $table->string('base_unit')->nullable()->after('unit_code');
            }
        });

        Schema::dropIfExists('units');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('short_name')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('sub_category_id')->constrained('units')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->after('supplier_id')->constrained('currencies')->onDelete('set null');
            $table->dropColumn(['unit_name', 'unit_code', 'base_unit']);
        });
    }
};
