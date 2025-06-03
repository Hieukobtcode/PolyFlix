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
            $table->json('cau_truc_ghe')->nullable(); 

            $table->tinyInteger('so_hang_thuong')->default(0); 
            $table->tinyInteger('so_hang_vip')->default(0);    
            $table->tinyInteger('so_hang_doi')->default(0);     

            $table->string('mo_ta')->nullable(); 

            $table->boolean('trang_thai')->default(1); 

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so_do_ghes');
    }
}
