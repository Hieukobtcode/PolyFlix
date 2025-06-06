<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGheNgoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ghe_ngois', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phong_chieu_id');
            $table->foreign('phong_chieu_id')
                ->references('id')
                ->on('phong_chieus')
                ->onDelete('cascade');
            $table->string('loai_ghe', 255);

            $table->char('hang', 2);

            $table->integer('cot');

            $table->string('ma_ghe', 10);

            $table->enum('trang_thai', ['trong', 'da_dat'])->default('trong');

           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ghe_ngois', function (Blueprint $table) {
            $table->dropForeign(['phong_chieu_id']);
        });

        Schema::dropIfExists('ghe_ngois');
    }
}
