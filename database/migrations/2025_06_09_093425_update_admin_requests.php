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
        //
        Schema::table('admin_requests', function (Blueprint $table) {
            $table->enum('loai_quan_ly', ['chi_nhanh', 'rap_phim'])->default('chi_nhanh');
            $table->unsignedBigInteger('rap_phim_id')->nullable()->after('chi_nhanh_id');
        
            $table->foreign('rap_phim_id')->references('id')->on('rap_phims')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_requests', function (Blueprint $table) {
            // Xóa foreign key trước khi xóa cột
            $table->dropForeign(['rap_phim_id']);
            $table->dropColumn(['loai_quan_ly', 'rap_phim_id']);
        });
    }
};
