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
        Schema::table('transactions', function (Blueprint $table) {
            // Add payment_image column
            $table->string('payment_image')->nullable()->after('payment_number');
            $table->integer('original_price')->nullable()->after('total_amount');
            $table->integer('discount_price')->nullable()->after('original_price');
            $table->string('invoice_id')->nullable()->after('invoice_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop payment_image column
            $table->dropColumn('payment_image');
            $table->dropColumn('original_price');
            $table->dropColumn('discount_price');
            $table->dropColumn('invoice_id');
        });
    }
};
