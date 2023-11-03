<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->after('uuid')->constrained('invoice_people');
            $table->foreignId('buyer_id')->nullable()->after('seller_id')->constrained('invoice_people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['buyer_id']);
            $table->dropColumn(['seller_id', 'buyer_id']);
        });
    }
};
