<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('do_ans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danh_muc_id')->constrained('danh_muc_do_ans')->onDelete('cascade');

            $table->string('tieu_de', 255);
            $table->text('noi_dung')->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->enum('trang_thai', ['hien', 'an'])->default('hien');
            $table->decimal('gia', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_ans');
    }
};
