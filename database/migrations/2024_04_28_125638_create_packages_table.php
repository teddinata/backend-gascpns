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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->integer('total_question')->nullable();
            $table->integer('total_duration')->nullable();
            $table->boolean('status')->default(0)->comment('0: draft, 1: published');
            $table->datetime('sale_start_at')->nullable();
            $table->datetime('sale_end_at')->nullable();
            $table->integer('discount')->nullable();
            // voucher
            $table->string('voucher_code')->nullable();

            $table->string('cover_path')->nullable();
            $table->string('thumbnail_path')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();



            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
