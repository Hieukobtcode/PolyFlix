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
        Schema::create('phim_chi_nhanh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phim_id')->constrained('phims')->onDelete('cascade');
            $table->foreignId('chi_nhanh_id')->constrained('chi_nhanhs')->onDelete('cascade');
            $table->timestamps();

            // Đảm bảo không trùng lặp
            $table->unique(['phim_id', 'chi_nhanh_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_chi_nhanh');
    }
};
