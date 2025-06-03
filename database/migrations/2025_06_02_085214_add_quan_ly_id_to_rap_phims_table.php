<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            $table->unsignedBigInteger('quan_ly_id')->nullable()->after('chi_nhanh_id');

            // Nếu liên kết với bảng users (hoặc bảng quản lý nào đó), thêm dòng sau:
            // $table->foreign('quan_ly_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            // Nếu có foreign key thì phải drop trước
            // $table->dropForeign(['quan_ly_id']);
            $table->dropColumn('quan_ly_id');
        });
    }
};
