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
    Schema::table('ghe_ngois', function (Blueprint $table) {
        $table->unsignedBigInteger('dang_chon_user_id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('ghe_ngois', function (Blueprint $table) {
        $table->dropColumn('dang_chon_user_id');
    });
}

};
