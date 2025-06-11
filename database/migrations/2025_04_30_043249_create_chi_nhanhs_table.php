<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiNhanhsTable extends Migration
{
    public function up()
    {
        Schema::create('chi_nhanhs', function (Blueprint $table) {
            $table->id(); 
            $table->string('ten_chi_nhanh'); 
            $table->text('dia_chi'); 
            $table->unsignedBigInteger('quan_ly_id'); 
            $table->enum('trang_thai', ['hoat_dong', 'tam_dung', 'dong_cua'])->default('hoat_dong'); 
            $table->timestamps();

            // Nếu cần ràng buộc khóa ngoại:
            // $table->foreign('quan_ly_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_nhanhs');
    }
}
