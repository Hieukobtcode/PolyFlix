<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->foreignId('chi_nhanh_id')
                ->nullable()
                ->constrained('chi_nhanhs')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->dropForeign(['chi_nhanh_id']);
            $table->dropColumn('chi_nhanh_id');
        });
    }
};
