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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('invoice_no')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->decimal('amount', 24, 4);
            $table->decimal('discount', 24, 4)->default(0);
            $table->decimal('tax', 24, 4)->default(0);
            $table->decimal('shipping', 24, 4)->default(0);
            $table->decimal('total', 24, 4);
            $table->enum('status', ['draft', 'unpaid', 'paid', 'canceled'])->default('draft');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
