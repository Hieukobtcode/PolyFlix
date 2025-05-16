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
        Schema::create('lich_su_su_dung_khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khuyen_mai_id');
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->dateTime('thoi_gian_su_dung');
            $table->foreign('khuyen_mai_id')->references('id')->on('khuyen_mais')->onDelete('cascade');
            $table->foreign('nguoi_dung_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_su_dung_khuyen_mais');
    }
};
