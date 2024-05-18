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
        Schema::table('try_out_details', function (Blueprint $table) {
            //
            // $table->integer('course_answer_id')->nullable()->after('course_question_id');
            $table->unsignedBigInteger('course_answer_id')->nullable()->after('course_question_id');
            // $table->foreignId('course_answer_id')->nullable()->after('course_question_id')->constrained()->onDelete('cascade');
            // delete answer id
            // $table->dropColumn('answer_id');

            // $table->unsignedBigInteger('answer_id'); // Kolom baru answer_id, bisa null
            // $table->foreign('answer_id')->references('id')->on('course_answers'); // Foreign key ke tabel course_answers
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('try_out_details', function (Blueprint $table) {
            //
            // $table->dropColumn('course_answer_id');
            // $table->dropForeign(['course_answer_id']);
            // $table->dropForeign(['answer_id']); // Menghapus foreign key constraint
            $table->dropColumn('answer_id'); // Menghapus kolom answer_id
        });
    }
};
