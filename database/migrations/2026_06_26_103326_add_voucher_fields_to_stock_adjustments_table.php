<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->index()->after('id');
            $table->date('transaction_date')->nullable()->after('notes');
        });

        // Backfill existing adjustments
        foreach (DB::table('stock_adjustments')->get() as $adj) {
            DB::table('stock_adjustments')
                ->where('id', $adj->id)
                ->update([
                    'voucher_no' => 'ADJ-MIG-'.$adj->id,
                    'transaction_date' => $adj->created_at ? date('Y-m-d', strtotime($adj->created_at)) : date('Y-m-d'),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropColumn(['voucher_no', 'transaction_date']);
        });
    }
};
