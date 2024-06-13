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
        Schema::table('users', function (Blueprint $table) {
            // Add location columns
            $table->string('province_id')->nullable()->after('address');
            $table->string('regency_id')->nullable()->after('province_id');
            $table->string('district_id')->nullable()->after('regency_id');
            $table->string('village_id')->nullable()->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop location columns
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
        });
    }
};
