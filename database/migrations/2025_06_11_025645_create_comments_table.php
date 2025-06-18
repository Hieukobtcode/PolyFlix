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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
              $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('phim_id')->constrained('phims')->onDelete('cascade');
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5 sao
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
