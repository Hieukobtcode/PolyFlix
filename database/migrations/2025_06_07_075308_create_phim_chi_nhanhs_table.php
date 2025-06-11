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
        Schema::create('phim_chi_nhanhs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('chi_nhanh_id');
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanhs');
            $table->unique(['phim_id', 'chi_nhanh_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_chi_nhanhs');
    }
};