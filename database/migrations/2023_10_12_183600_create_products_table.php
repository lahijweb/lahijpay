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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('description');
            $table->decimal('price', 24, 4);
            $table->integer('qty')->nullable();
            $table->enum('type', ['physical', 'digital'])->default('digital');
            $table->enum('status', ['draft', 'published', 'unpublished', 'archived'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_scheduled')->default(false);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
