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
    Schema::create('dat_ve_do_an', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('dat_ve_id');
        $table->unsignedBigInteger('do_an_id');
        $table->integer('so_luong')->default(1);
        $table->timestamps();

        $table->foreign('dat_ve_id')->references('id')->on('dat_ves')->onDelete('cascade');
        $table->foreign('do_an_id')->references('id')->on('do_ans')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_ve_do_an');
    }
};
