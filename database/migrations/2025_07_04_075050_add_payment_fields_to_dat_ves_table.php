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
        Schema::table('dat_ves', function (Blueprint $table) {
            // Thêm các cột thanh toán
            $table->string('ma_giao_dich')->nullable()->after('tong_tien')
                ->comment('Mã giao dịch từ cổng thanh toán');
            $table->timestamp('ngay_thanh_toan')->nullable()->after('ma_giao_dich')
                ->comment('Thời gian thanh toán thành công');
            $table->text('ghi_chu')->nullable()->after('ngay_thanh_toan')
                ->comment('Ghi chú thêm (lý do thất bại, thông tin bổ sung)');
        });

        // Cập nhật enum trang_thai để thêm các trạng thái mới
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->enum('trang_thai', [
                'Chờ thanh toán',
                'Đã thanh toán',
                'Thanh toán thất bại',
                'Chờ thanh toán tại quầy',
                'Đã hủy',
                'Hết hạn',
                'Chưa xuất vé',
                'Đã xuất vé',
            ])->default('Chờ thanh toán')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn(['ma_giao_dich', 'ngay_thanh_toan', 'ghi_chu']);
        });

        // Khôi phục enum cũ
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->enum('trang_thai', [
                'Chờ thanh toán',
                'Đã thanh toán',
                'Đã hủy',
                'Hết hạn',
                'Chưa xuất vé',
                'Đã xuất vé',
            ])->default('Chờ thanh toán')->change();
        });
    }
};
