<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faq_id');
            $table->string('question');
            $table->text('answer');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // ✅ must be inside the closure for soft deletes to work
            // Foreign key
            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_faqs');
    }
};