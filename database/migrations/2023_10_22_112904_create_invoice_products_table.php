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
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('product_sku');
            $table->string('product_title');
            $table->integer('product_qty')->default(1);
            $table->decimal('product_price', 24, 4);
            $table->decimal('discount', 24, 4)->default(0);
            $table->decimal('tax', 24, 4)->default(0);
            $table->decimal('total', 24, 4);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
