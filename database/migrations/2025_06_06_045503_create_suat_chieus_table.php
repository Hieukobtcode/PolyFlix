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
        Schema::create('suat_chieus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('phim_id');
            $table->unsignedBigInteger('chi_nhanh_id');
            $table->unsignedBigInteger('rap_phim_id')->nullable();
            $table->unsignedBigInteger('phong_chieu_id');
            $table->enum('phien_ban_phim', ['long_tieng', 'phu_de']);
            $table->date('ngay_chieu');
            $table->dateTime('bat_dau');
            $table->dateTime('ket_thuc');
            $table->enum('trang_thai', ['hoat_dong', 'tam_dung'])->default('hoat_dong');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Khóa ngoại
            $table->foreign('phim_id')->references('id')->on('phims');
            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanhs');
            $table->foreign('rap_phim_id')->references('id')->on('rap_phims');
            $table->foreign('phong_chieu_id')->references('id')->on('phong_chieus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suat_chieus');
    }
};