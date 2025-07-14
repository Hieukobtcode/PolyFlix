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
        Schema::create('rap_combo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rap_phim_id')->constrained('rap_phims')->onDelete('cascade');
            $table->foreignId('combo_id')->constrained('combos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rap_combo');
    }
};
