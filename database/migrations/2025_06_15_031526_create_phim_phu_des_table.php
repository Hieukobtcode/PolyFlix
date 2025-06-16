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
        Schema::create('phim_phu_des', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('phu_de_phim_id');
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('phu_de_phim_id')->references('id')->on('phu_de_phims');
            $table->unique(['phim_id', 'phu_de_phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_phu_des');
    }
};