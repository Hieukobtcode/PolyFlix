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
        Schema::create('cau_hinhs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_website', 255)->nullable();
            $table->string('ten_thuong_hieu', 255)->nullable();
            $table->string('khau_hieu', 255)->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('dia_chi')->nullable();
            $table->text('giay_phep_kinh_doanh')->nullable();
            $table->string('thoi_gian_lam_viec', 100)->nullable();
            $table->string('link_facebook', 255)->nullable();
            $table->string('link_youtube', 255)->nullable();
            $table->string('ban_quyen', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('anh_chinh_sach_bao_mat', 255)->nullable();
            $table->string('anh_dieu_khoan_dich_vu', 255)->nullable();
            $table->string('anh_gioi_thieu', 255)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hinhs');
    }
};
