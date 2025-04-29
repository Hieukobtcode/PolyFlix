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
        Schema::create('the_loai_phims', function (Blueprint $table) {
            $table->id();
            $table->string('ten_the_loai', 255);
            $table->text('mo_ta')->nullable();
            $table->enum('trang_thai', ['hoạt động', 'không hoạt động']);
            $table->timestamp('create_at');
            $table->timestamp('update_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_loai_phims');
    }
};
