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
        Schema::table('phims', function (Blueprint $table) {
            $table->date('ngay_ket_thuc')->nullable()->after('ngay_phat_hanh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phims', function (Blueprint $table) {
            $table->dropColumn('ngay_ket_thuc');
        });
    }
};