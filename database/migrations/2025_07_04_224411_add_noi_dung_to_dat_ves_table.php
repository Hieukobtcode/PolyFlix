<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->text('noi_dung_chuyen_khoan')->nullable()->after('tong_tien');
        });
    }

    public function down(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->dropColumn('noi_dung_chuyen_khoan');
        });
    }
};
