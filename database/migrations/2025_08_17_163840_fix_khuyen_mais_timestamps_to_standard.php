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
        Schema::table('khuyen_mais', function (Blueprint $table) {
            // Kiểm tra và đổi tên cột timestamps nếu cần
            if (Schema::hasColumn('khuyen_mais', 'create_at')) {
                $table->renameColumn('create_at', 'created_at');
            }
            if (Schema::hasColumn('khuyen_mais', 'update_at')) {
                $table->renameColumn('update_at', 'updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khuyen_mais', function (Blueprint $table) {
            // Đổi ngược lại nếu cần rollback
            if (Schema::hasColumn('khuyen_mais', 'created_at')) {
                $table->renameColumn('created_at', 'create_at');
            }
            if (Schema::hasColumn('khuyen_mais', 'updated_at')) {
                $table->renameColumn('updated_at', 'update_at');
            }
        });
    }
};
