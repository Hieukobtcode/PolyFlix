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
        Schema::create('chi_tiet_dat_ves', function (Blueprint $table) {
            $table->id(); // id

            $table->unsignedBigInteger('dat_ve_id');
            $table->unsignedBigInteger('suat_chieu_id');
            $table->unsignedBigInteger('ghe_id');

            $table->enum('trang_thai_ghe', ['da_dat', 'da_huy', 'cho_xac_nhan'])->default('da_dat');
            $table->enum('loai_ve', ['nguoi_lon', 'tre_em', 'sinh_vien', 'khuyen_mai']);
            $table->decimal('gia_ve', 15, 2);

            $table->unsignedBigInteger('khuyen_mai_id')->nullable();
            $table->dateTime('ngay_tao');

            $table->timestamps();

            // Foreign key
            $table->foreign('dat_ve_id')->references('id')->on('dat_ves')->onDelete('cascade');
            $table->foreign('suat_chieu_id')->references('id')->on('suat_chieus')->onDelete('cascade');
            $table->foreign('ghe_id')->references('id')->on('ghe_ngois')->onDelete('cascade');
            $table->foreign('khuyen_mai_id')->references('id')->on('khuyen_mais')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dat_ves');
    }
};
