<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterQuanLyIdNullableInChiNhanhsTable extends Migration
{
    public function up()
    {
        Schema::table('chi_nhanhs', function (Blueprint $table) {
            $table->unsignedBigInteger('quan_ly_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('chi_nhanhs', function (Blueprint $table) {
            $table->unsignedBigInteger('quan_ly_id')->nullable(false)->change();
        });
    }
}
