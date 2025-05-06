<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phims', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phim', 255);
            $table->text('mo_ta')->nullable();
            $table->string('dao_dien', 255)->nullable();
            $table->text('dien_vien')->nullable();
            $table->integer('thoi_luong')->nullable();
            $table->date('ngay_phat_hanh')->nullable();
            $table->string('trailer', 255)->nullable();
            $table->string('poster', 255)->nullable();
            $table->string('ngon_ngu', 50)->nullable();
            $table->string('quoc_gia', 50)->nullable();
            $table->string('do_tuoi', 50)->nullable();
            $table->enum('trang_thai', ['đang chiếu', 'sắp chiếu', 'đã kết thúc', 'bị hủy']);
            $table->softDeletes();
            $table->timestamp('create_at');
            $table->timestamp('update_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phims');
    }
};
