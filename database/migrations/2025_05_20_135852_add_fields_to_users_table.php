<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dia_chi')->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('trang_thai'); // ví dụ: hoat_dong, khoa, ...
            $table->boolean('hoat_dong'); // true = đang hoạt động
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dia_chi', 'so_dien_thoai', 'trang_thai', 'hoat_dong']);
        });
    }
};
