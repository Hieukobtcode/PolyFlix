<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('chi_nhanh_do_an', function (Blueprint $table) {
        $table->id();
        $table->foreignId('do_an_id')->constrained('do_ans')->onDelete('cascade');
        $table->foreignId('chi_nhanh_id')->constrained('chi_nhanhs')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_nhanh_do_an');
    }
};
