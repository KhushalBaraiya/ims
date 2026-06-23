
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'base_unit')) {
                $table->dropColumn('base_unit');
            }
            if (Schema::hasColumn('products', 'opening_stock')) {
                $table->dropColumn('opening_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('base_unit')->nullable()->after('unit_code');
            $table->decimal('opening_stock', 15, 2)->default(0.00)->after('discount_percentage');
        });
    }
};
