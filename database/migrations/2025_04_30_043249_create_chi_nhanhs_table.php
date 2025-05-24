<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiNhanhsTable extends Migration
{
    public function up()
    {
        Schema::create('chi_nhanhs', function (Blueprint $table) {
            $table->id(); // id - INT tự tăng
            $table->string('ten_chi_nhanh'); // tên chi nhánh - VARCHAR
            $table->text('dia_chi'); // địa chỉ - TEXT
            $table->unsignedBigInteger('quan_ly_id'); // quản lý id - INT
            $table->enum('trang_thai', ['hoat_dong', 'tam_dung', 'dong_cua'])->default('hoat_dong'); // trạng thái - ENUM
            $table->timestamps(); // created_at và updated_at

            // Nếu cần ràng buộc khóa ngoại:
            // $table->foreign('quan_ly_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_nhanhs');
    }
}
