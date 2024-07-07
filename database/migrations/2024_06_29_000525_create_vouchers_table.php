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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->integer('discount_amount')->default(0);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->boolean('is_active')->default(true);
            // min quantity of purchase to use this voucher
            $table->integer('min_quantity')->nullable();
            $table->decimal('min_purchase', 8, 2)->nullable();
            $table->decimal('max_discount', 8, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('vouchers', function (Blueprint $table) {
        //     $table->dropColumn('id');
        //     $table->dropColumn('code');
        //     $table->dropColumn('discount_amount');
        //     $table->dropColumn('discount_type');
        //     $table->dropColumn('valid_from');
        //     $table->dropColumn('valid_to');
        //     $table->dropColumn('is_active');
        //     $table->dropTimestamps();
        // });
        Schema::dropIfExists('vouchers');
    }
};
