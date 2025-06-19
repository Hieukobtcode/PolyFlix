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
            $table->renameColumn('tong_chi_tieu', 'tong_so_ve_da_mua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cap_bac_thes', function (Blueprint $table) {
            $table->renameColumn('tong_so_ve_da_mua', 'tong_chi_tieu');
        });
    }
};