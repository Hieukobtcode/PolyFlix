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
        Schema::create('phim_dinh_dangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('dinh_dang_phim_id');
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('dinh_dang_phim_id')->references('id')->on('dinh_dang_phims');
            $table->unique(['phim_id', 'dinh_dang_phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_dinh_dangs');
    }
};