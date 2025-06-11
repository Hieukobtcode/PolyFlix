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
        Schema::create('cap_bac_thes', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('mo_ta');
            $table->integer('tong_chi_tieu');
            $table->integer('phan_tram_ve');
            $table->integer('phan_tram_dich_vu');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cap_bac_thes');
    }
};
