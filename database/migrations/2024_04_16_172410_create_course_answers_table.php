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
        Schema::create('course_answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->integer('score')->default(0)->nullable();
            $table->foreignId('course_question_id')->constrained()->onDelete('cascade');

            // image for the answer
            $table->string('image')->nullable();

            // created_by is the user who created the answer
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_answers');
    }
};
