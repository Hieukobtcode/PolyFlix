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
        Schema::create('lien_he_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lien_he_id')->constrained('lien_hes')->onDelete('cascade');
            $table->string('hanh_dong', 50);
            $table->string('mo_ta')->nullable();
            $table->string('nguoi_thuc_hien', 100)->nullable();
            $table->json('du_lieu_cu')->nullable();
            $table->json('du_lieu_moi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_he_activity_logs');
    }
};
