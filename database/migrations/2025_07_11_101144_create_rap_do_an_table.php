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
        Schema::create('rap_do_an', function (Blueprint $table) {
            $table->id();
            $table->foreignId('do_an_id')->constrained()->onDelete('cascade');
            $table->foreignId('rap_phim_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rap_do_an');
    }
};
