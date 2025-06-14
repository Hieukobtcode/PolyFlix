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
        Schema::create('dat_ves', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->unsignedBigInteger('nguoi_dung_id'); // Foreign key đến bảng users

            $table->decimal('tong_tien', 15, 2);
            $table->decimal('khuyen_mai', 15, 2)->nullable();
            $table->decimal('tong_tien_thanh_toan', 15, 2);

            $table->dateTime('thoi_gian_dat');
            $table->enum('phuong_thuc_thanh_toan', [ 'zalo_pay']);
            
            $table->text('ghi_chu')->nullable();
            $table->dateTime('ngay_cap_nhat')->nullable();

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('nguoi_dung_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_ves');
    }
};
