<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoaiGhesTable extends Migration
{
    public function up()
    {
        Schema::create('loai_ghes', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai_ghe', 255);
            $table->text('mo_ta')->nullable();
            $table->decimal('phu_thu', 5, 2)->default(0);
            $table->timestamps(); // Tạo 2 cột: created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('loai_ghes');
    }
}
