<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoDoGhesTable extends Migration
{
    public function up(): void
    {
        Schema::create('so_do_ghes', function (Blueprint $table) {
            $table->id();
            $table->string('ten_so_do');
            $table->tinyInteger('so_hang');
            $table->tinyInteger('so_cot');
            $table->json('cau_truc')->nullable(); // ví dụ: {"A1":1, "A2":2}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so_do_ghes');
    }
}
