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
        Schema::create('ghe_ngoi_suat_chieu', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ghe_ngoi_id');
            $table->unsignedBigInteger('suat_chieu_id');

            $table->enum('trang_thai', ['trong', 'da_chon', 'da_dat'])->default('trong');

            $table->unsignedBigInteger('user_id')->nullable(); // Người đang chọn/đặt (nếu có)

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('ghe_ngoi_id')->references('id')->on('ghe_ngois')->onDelete('cascade');
            $table->foreign('suat_chieu_id')->references('id')->on('suat_chieus')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Ràng buộc mỗi ghế chỉ có 1 trạng thái cho mỗi suất chiếu
            $table->unique(['ghe_ngoi_id', 'suat_chieu_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ghe_ngoi_suat_chieu');
    }

};
