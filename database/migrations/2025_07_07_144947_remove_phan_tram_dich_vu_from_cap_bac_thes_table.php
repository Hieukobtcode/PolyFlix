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
        Schema::table('cap_bac_thes', function (Blueprint $table) {
            $table->dropColumn('phan_tram_dich_vu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cap_bac_thes', function (Blueprint $table) {
            $table->integer('phan_tram_dich_vu');
        });
    }
};