<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ghe_ngois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phong_chieu_id')->constrained('phong_chieus')->cascadeOnDelete();
            $table->foreignId('loai_ghe_id')->nullable()->constrained('loai_ghes')->nullOnDelete();
            $table->string('so_hang', 2);       // A, B, C...
            $table->tinyInteger('so_cot');      // 1, 2, 3...
            $table->string('ma_ghe', 10);       // A1, B3...
            $table->enum('trang_thai', ['sẵn sàng', 'đã giữ', 'đã đặt', 'không dùng'])->default('sẵn sàng');
            $table->timestamps();

            $table->unique(['phong_chieu_id', 'ma_ghe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ghe_ngois');
    }
};
