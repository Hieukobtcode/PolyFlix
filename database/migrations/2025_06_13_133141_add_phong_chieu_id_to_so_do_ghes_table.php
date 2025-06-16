<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhongChieuIdToSoDoGhesTable extends Migration
{
    public function up()
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            // Kiểm tra nếu cột chưa tồn tại thì mới thêm
            if (!Schema::hasColumn('so_do_ghes', 'phong_chieu_id')) {
                $table->unsignedBigInteger('phong_chieu_id')->after('id')->nullable();

                // Nếu có bảng phong_chieus, thêm khóa ngoại
                $table->foreign('phong_chieu_id')
                    ->references('id')->on('phong_chieus')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            if (Schema::hasColumn('so_do_ghes', 'phong_chieu_id')) {
                $table->dropForeign(['phong_chieu_id']);
                $table->dropColumn('phong_chieu_id');
            }
        });
    }
}
