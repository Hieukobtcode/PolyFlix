<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->dropColumn('phu_thu'); // xóa cột cũ nếu là decimal
        });

        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->integer('phu_thu')->default(0); // thêm lại dạng integer
        });
    }

    public function down(): void
    {
        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->dropColumn('phu_thu');
        });

        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->decimal('phu_thu', 5, 2)->default(0);
        });
    }
};
