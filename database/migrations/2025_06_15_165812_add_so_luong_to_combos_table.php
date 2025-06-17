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
    Schema::table('combos', function (Blueprint $table) {
        $table->integer('so_luong')->default(1)->after('gia_combo');
    });
}

public function down(): void
{
    Schema::table('combos', function (Blueprint $table) {
        $table->dropColumn('so_luong');
    });
}
};
