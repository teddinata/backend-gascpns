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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();

            // name of the package
            $table->string('package_name')->nullable();
            $table->integer('package_price')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('price')->default(0);

            $table->timestamps();

            // index
            $table->index('transaction_id');
            $table->index('package_name');
            $table->index('package_price');
            $table->index('quantity');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
