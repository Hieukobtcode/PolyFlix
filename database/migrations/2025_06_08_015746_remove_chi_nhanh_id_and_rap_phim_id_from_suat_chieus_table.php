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
            // Xóa khóa ngoại trước
            $table->dropForeign(['chi_nhanh_id']);
            $table->dropForeign(['rap_phim_id']);

            // Sau đó xóa cột
            $table->dropColumn(['chi_nhanh_id', 'rap_phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suat_chieus', function (Blueprint $table) {
            $table->unsignedBigInteger('chi_nhanh_id')->after('phim_id');
            $table->unsignedBigInteger('rap_phim_id')->nullable()->after('chi_nhanh_id');

            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanhs');
            $table->foreign('rap_phim_id')->references('id')->on('rap_phims');
        });
    }
    
};