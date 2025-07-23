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
            // Đổi tên cột 'ngay_chieu' thành 'ngay_bat_dau'
            $table->renameColumn('ngay_chieu', 'ngay_bat_dau');

            // Thêm cột 'ngay_ket_thuc' (bắt buộc)
            $table->date('ngay_ket_thuc')->nullable()->after('ngay_bat_dau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suat_chieus', function (Blueprint $table) {
            // Đổi tên lại
            $table->renameColumn('ngay_bat_dau', 'ngay_chieu');

            // Xoá cột
            $table->dropColumn('ngay_ket_thuc');
        });
    }
};