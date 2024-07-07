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
            //
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers');
            // $table->decimal('discount_amount', 8, 2)->default(0);
            $table->integer('discount_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
            $table->dropForeign(['voucher_id']);
            // $table->dropColumn('voucher_id');
            $table->dropColumn('discount_amount');
        });
    }
};
