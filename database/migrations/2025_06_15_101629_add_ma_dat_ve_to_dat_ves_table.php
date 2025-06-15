<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->string('ma_dat_ve')->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->dropColumn('ma_dat_ve');
        });
    }
};
