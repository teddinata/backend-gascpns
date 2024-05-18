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
        Schema::table('tryouts', function (Blueprint $table) {
            // add column status
            $table->enum('status_pengerjaan', ['belum dikerjakan', 'sedang dikerjakan', 'sudah dikerjakan'])->default('belum dikerjakan')->after('finished_at');
            $table->integer('status')->default(0)->comment('0: belum dikerjakan, 1: sedang dikerjakan, 2: sudah dikerjakan')->after('status_pengerjaan');
            $table->timestamp('finish_time')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tryouts', function (Blueprint $table) {
            // drop column status
            $table->dropColumn('status_pengerjaan');
            $table->dropColumn('status');
            $table->dropColumn('finish_time');
        });
    }
};
