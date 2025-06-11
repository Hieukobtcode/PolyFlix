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
            // Thay đổi kiểu dữ liệu cột bat_dau và ket_thuc thành time
            $table->time('bat_dau')->change();
            $table->time('ket_thuc')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suat_chieus', function (Blueprint $table) {
            // Khôi phục lại kiểu dữ liệu cũ là dateTime nếu rollback
            $table->dateTime('bat_dau')->change();
            $table->dateTime('ket_thuc')->change();
        });
    }
};