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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // invoice
            $table->string('invoice_code')->unique();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('student_id_transaction')->nullable();

            $table->foreignId('package_id')->constrained()->onDelete('cascade');

            // tax
            $table->integer('tax')->default(0);
            $table->integer('total_amount')->default(0);

            $table->string('payment_status')->default('unpaid')->comment('unpaid, pending, paid, failed');
            $table->string('payment_id')->nullable();
            $table->text('payment_response')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('payment_expired')->nullable();
            $table->string('payment_token')->nullable();
            $table->integer('payment_timer')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('payment_number')->nullable();

            $table->string('voucher_code')->nullable();
            $table->timestamps();

            $table->foreign('student_id_transaction')->references('id')->on('users')->onDelete('cascade');

            $table->index('invoice_code');
            $table->index('student_id');
            $table->index('package_id');
            $table->index('payment_status');
            $table->index('payment_id');
            $table->index('payment_method');
            $table->index('payment_date');
            $table->index('payment_expired');
            $table->index('payment_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
