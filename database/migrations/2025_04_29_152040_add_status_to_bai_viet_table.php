<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBaiVietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bai_viets', function (Blueprint $table) {
            $table->string('status')->default('draft'); // Thêm cột trạng thái với giá trị mặc định là 'draft'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bai_viets', function (Blueprint $table) {
            $table->dropColumn('status'); // Xóa cột trạng thái nếu rollback migration
        });
    }
}
