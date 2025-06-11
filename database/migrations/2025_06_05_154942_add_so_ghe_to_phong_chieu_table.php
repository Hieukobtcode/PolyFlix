<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoGheToPhongChieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phong_chieus', function (Blueprint $table) {
            $table->integer('so_ghe')->nullable();  // Đảm bảo trường này có thể null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phong_chieus', function (Blueprint $table) {
            $table->dropColumn('so_ghe');  // Nếu rollback sẽ xóa trường này
        });
    }
}
