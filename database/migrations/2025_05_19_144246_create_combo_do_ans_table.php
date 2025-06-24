<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('combo_do_ans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('combo_id')->constrained()->onDelete('cascade');
        $table->foreignId('do_an_id')->constrained('do_ans')->onDelete('cascade');
        $table->integer('so_luong')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_do_ans');
    }
};
