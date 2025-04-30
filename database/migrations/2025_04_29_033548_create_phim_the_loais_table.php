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
        Schema::create('phim_the_loais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('the_loai_phim_id');
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('the_loai_phim_id')->references('id')->on('the_loai_phims');
            $table->unique(['phim_id', 'the_loai_phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_the_loais');
    }
};
