<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // The currency used for this purchase (supplier's currency)
            $table->unsignedBigInteger('currency_id')->nullable()->after('supplier_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();

            // Exchange rate at time of purchase: how many base-currency units = 1 supplier-currency unit
            // e.g. if supplier uses USD and 1 USD = 83.5 INR, store 83.5
            // All monetary amounts in this table are stored in BASE currency
            $table->decimal('exchange_rate', 15, 4)->default(1.0000)->after('currency_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['currency_id', 'exchange_rate']);
        });
    }
};
