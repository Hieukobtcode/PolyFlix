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
        Schema::create('vai_tros', function (Blueprint $table) {
            $table->id();  // trường ID tự tăngtăng
            $table->string('ten')->unique();  // Tên vai trò
            $table->string('mo_ta')->nullable();  // Mô tả vai trò
            $table->timestamps();  // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('vai_tros');  // Xóa bảng vai_tros nếu không cần thiết
    }
};
