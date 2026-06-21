<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('save_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_holder_name');
            $table->string('last_four_digits', 4);
            $table->string('expiry_month', 2);
            $table->string('expiry_year', 4);
            $table->string('card_brand')->nullable(); // Visa, Mastercard, etc.
            $table->string('gateway_token')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('save_cards');
    }
};
