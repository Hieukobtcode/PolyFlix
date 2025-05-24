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
        Schema::create('chi_tiet_khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khuyen_mai_id');
            $table->unsignedBigInteger('chi_nhanh_id');
            $table->foreign('khuyen_mai_id')->references('id')->on('khuyen_mais')->onDelete('cascade');
            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanhs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_khuyen_mais');
    }
};
