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
        Schema::create('rap_phims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chi_nhanh_id'); 
            $table->string('ten_rap', 255); 
            $table->text('dia_chi'); 
            $table->text('so_dien_thoai'); 
            $table->string('email', 255)->unique(); 
            $table->enum('trang_thai', ['đang hoạt động', 'bảo trì', 'đã đóng' ]); // ENUM trạng thái
            $table->softDeletes(); // hỗ trợ soft delete
  
            $table->timestamps();
            $table->foreign('chi_nhanh_id')->references('id')->on('chi_nhanhs')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rap_phims');
    }
};
