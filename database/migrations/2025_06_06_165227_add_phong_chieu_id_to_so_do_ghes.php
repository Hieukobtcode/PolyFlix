<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhongChieuIdToSoDoGhes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->unsignedBigInteger('phong_chieu_id')->after('id');

            $table->foreign('phong_chieu_id')
                  ->references('id')->on('phong_chieus')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->dropForeign(['phong_chieu_id']);
            $table->dropColumn('phong_chieu_id');
        });
    }
}
