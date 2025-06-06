<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrangThaiInGheNgoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ghe_ngois', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });

        Schema::table('ghe_ngois', function (Blueprint $table) {
            $table->enum('trang_thai', [
                'trong',          
                'da_chon',         
                'da_dat',        
                'dang_giu',       
                'khong_kha_dung',  
                'bao_tri'        
            ])->default('trong')->after('ma_ghe');
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
            $table->dropColumn('trang_thai');
        });

        Schema::table('ghe_ngois', function (Blueprint $table) {
            $table->enum('trang_thai', ['trong', 'da_dat'])->default('trong');
        });
    }
}
