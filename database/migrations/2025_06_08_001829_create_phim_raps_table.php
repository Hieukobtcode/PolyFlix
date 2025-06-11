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
        Schema::create('phim_raps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('rap_phim_id');
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('rap_phim_id')->references('id')->on('rap_phims');
            $table->unique(['phim_id', 'rap_phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_raps');
    }
};