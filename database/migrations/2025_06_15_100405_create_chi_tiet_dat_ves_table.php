<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chi_tiet_dat_ves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dat_ve_id')->constrained('dat_ves')->onDelete('cascade');
            $table->foreignId('ghe_id')->constrained('ghe_ngois')->onDelete('cascade');

            $table->decimal('gia_ve', 10, 2);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dat_ves');
    }
};

