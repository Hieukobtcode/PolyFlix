<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            $table->unsignedInteger('phu_thu')->default(50000)->after('trang_thai');
        });
    }

    public function down()
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            $table->dropColumn('phu_thu');
        });
    }
};
