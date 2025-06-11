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
        // Kiểm tra xem bảng có tồn tại không
        if (Schema::hasTable('khuyen_mais')) {
            // Kiểm tra xem các cột create_at và update_at có tồn tại không
            if (Schema::hasColumn('khuyen_mais', 'create_at') && Schema::hasColumn('khuyen_mais', 'update_at')) {
                // Nếu có, không cần làm gì vì đã đúng
                return;
            }

            // Nếu chưa có, thêm các cột timestamp tùy chỉnh
            Schema::table('khuyen_mais', function (Blueprint $table) {
                if (!Schema::hasColumn('khuyen_mais', 'create_at')) {
                    $table->timestamp('create_at')->useCurrent();
                }
                if (!Schema::hasColumn('khuyen_mais', 'update_at')) {
                    $table->timestamp('update_at')->useCurrent()->useCurrentOnUpdate();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là migration sửa lỗi
    }
};
