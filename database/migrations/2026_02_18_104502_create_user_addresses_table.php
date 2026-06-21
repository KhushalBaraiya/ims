<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_country_code');
            $table->string('mobile_no');
            $table->string('email');
            $table->string('house_no');
            $table->string('landmark');
            $table->string('locality_area');
            $table->string('pincode');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->string('address_type')->default('home'); // home, work, other
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
