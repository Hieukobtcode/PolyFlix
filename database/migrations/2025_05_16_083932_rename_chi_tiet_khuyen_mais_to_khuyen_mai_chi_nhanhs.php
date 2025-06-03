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
        Schema::rename('chi_tiet_khuyen_mais', 'khuyen_mai_chi_nhanhs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('khuyen_mai_chi_nhanhs', 'chi_tiet_khuyen_mais');
    }
};
