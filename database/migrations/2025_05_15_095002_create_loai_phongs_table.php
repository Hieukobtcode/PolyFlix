<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loai_phongs', function (Blueprint $table) {
            $table->id(); // id - khóa chính
            $table->string('ten_loai_phong'); // tên loại phòng
            $table->text('mo_ta')->nullable(); // mô tả (có thể rỗng)
            $table->timestamp('create_at')->nullable(); // ngày tạo (tuỳ bạn có dùng timestamps không)
            $table->timestamp('update_at')->nullable(); // ngày cập nhật
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_phongs');
    }
};


