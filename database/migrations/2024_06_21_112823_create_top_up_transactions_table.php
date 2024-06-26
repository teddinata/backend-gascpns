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
        Schema::create('top_up_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('PENDING');
            $table->string('payment_token')->nullable();
            $table->string('payment_number')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('payment_channel')->nullable();
            $table->timestamp('payment_expired')->nullable();
            $table->longText('payment_response')->nullable();
            $table->string('payment_date')->nullable();
            $table->integer('payment_timer')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_transactions');
    }
};
