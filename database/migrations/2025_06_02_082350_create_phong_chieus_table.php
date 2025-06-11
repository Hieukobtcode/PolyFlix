<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phong_chieus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rap_phim_id')->constrained('rap_phims')->onDelete('cascade');
            $table->string('ten_phong');
            $table->foreignId('loai_phong_id')->nullable()->constrained('loai_phongs')->onDelete('set null');
            $table->foreignId('so_do_ghe_id')->nullable()->constrained('so_do_ghes')->onDelete('set null');
            $table->enum('status', ['hoat_dong', 'tam_dung', 'bao_tri', 'da_dong'])->default('hoat_dong');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phong_chieus');
    }
};
