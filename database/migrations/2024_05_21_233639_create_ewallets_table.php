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
        Schema::create('ewallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('ewallet_type');
            $table->string('country');
            $table->string('currency');
            $table->boolean('is_activated')->default(true);
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ewallets');
    }
};
