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
        Schema::create('khuyen_mai_rap_phims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khuyen_mai_id');
            $table->unsignedBigInteger('rap_phim_id');

            // Sử dụng timestamps tùy chỉnh như các bảng khác trong project
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('khuyen_mai_id')->references('id')->on('khuyen_mais')->onDelete('cascade');
            $table->foreign('rap_phim_id')->references('id')->on('rap_phims')->onDelete('cascade');

            // Unique constraint to prevent duplicates
            $table->unique(['khuyen_mai_id', 'rap_phim_id'], 'unique_khuyen_mai_rap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_rap_phims');
    }
};
