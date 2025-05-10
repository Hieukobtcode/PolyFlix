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
        Schema::create('bai_viets', function (Blueprint $table) {
            $table->id(); // tương đương id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('tieu_de', 255);
            $table->text('noi_dung');
            $table->string('hinh_anh', 255)->nullable();
            $table->dateTime('ngay_tao')->useCurrent();
            $table->dateTime('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_viets');
    }
};
