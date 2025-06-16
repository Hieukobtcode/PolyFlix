<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dat_ves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('suat_chieu_id')->constrained('suat_chieus')->onDelete('cascade');
            $table->foreignId('khuyen_mai_id')->nullable()->constrained('khuyen_mais')->onDelete('set null');

            $table->decimal('tong_tien', 10, 2);
            $table->string('phuong_thuc_tt', 50); 

            $table->enum('trang_thai', [
                'Chờ thanh toán',
                'Đã thanh toán',
                'Đã hủy',
                'Hết hạn',
                'Chưa xuất vé',
                'Đã xuất vé',
            ])->default('Chờ thanh toán');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dat_ves');
    }
};
