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
        Schema::table('quan_ly_invites', function (Blueprint $table) {
            $table->string('loai_quan_ly')->nullable()->after('email'); // 'chi_nhanh' hoặc 'rap_phim'
            $table->unsignedBigInteger('rap_phim_id')->nullable()->after('chi_nhanh_id');

            $table->foreign('rap_phim_id')->references('id')->on('rap_phims')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('quan_ly_invites', function (Blueprint $table) {
            $table->dropForeign(['rap_phim_id']);
            $table->dropColumn(['loai_quan_ly', 'rap_phim_id']);
        });
    }
};
