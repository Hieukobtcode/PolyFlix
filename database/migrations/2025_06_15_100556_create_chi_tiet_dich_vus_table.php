<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chi_tiet_dich_vus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dat_ve_id')->constrained('dat_ves')->onDelete('cascade');
            $table->foreignId('combo_id')->constrained('combos')->onDelete('cascade');

            $table->integer('so_luong');
            $table->decimal('gia_combo', 10, 2); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dich_vus');
    }
};
