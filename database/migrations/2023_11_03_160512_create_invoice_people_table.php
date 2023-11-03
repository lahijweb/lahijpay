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
        Schema::create('invoice_people', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['seller', 'buyer'])->default('buyer');
            $table->string('name');
            $table->string('identity_no')->nullable();
            $table->string('register_no')->nullable();
            $table->string('finance_no')->nullable();
            $table->string('phone')->nullable();
            $table->string('zip')->nullable();
            $table->string('address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_people');
    }
};
