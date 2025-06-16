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
        Schema::create('ratings', function (Blueprint $table) {
              $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('phim_id')->constrained()->onDelete('cascade');
    $table->tinyInteger('rating'); // 1 đến 5 sao
    $table->timestamps();

    $table->unique(['user_id', 'phim_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
