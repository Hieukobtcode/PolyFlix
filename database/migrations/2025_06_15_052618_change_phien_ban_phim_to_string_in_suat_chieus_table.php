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
        Schema::table('suat_chieus', function (Blueprint $table) {
            // Đổi kiểu cột phien_ban_phim từ enum thành string
            $table->string('phien_ban_phim', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suat_chieus', function (Blueprint $table) {
            // Nếu rollback, quay lại enum - lưu ý cập nhật danh sách giá trị phù hợp
            $table->enum('phien_ban_phim', ['long_tieng', 'phu_de'])->change();
        });
    }
};