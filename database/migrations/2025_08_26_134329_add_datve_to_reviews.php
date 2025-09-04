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
        Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('dat_ve_id')->after('user_id')->nullable();
            $table->foreign('dat_ve_id')->references('id')->on('dat_ves')->onDelete('cascade');

            // Mỗi vé chỉ có 1 rating
            $table->unique(['dat_ve_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('dat_ve_id')->after('user_id')->nullable();
            $table->foreign('dat_ve_id')->references('id')->on('dat_ves')->onDelete('cascade');

            // Mỗi vé chỉ có 1 comment
            $table->unique(['dat_ve_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['dat_ve_id']);
            $table->dropUnique(['dat_ve_id']);
            $table->dropColumn('dat_ve_id');

            // Khôi phục ràng buộc unique cũ nếu cần
            $table->unique(['user_id', 'phim_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['dat_ve_id']);
            $table->dropUnique(['dat_ve_id']);
            $table->dropColumn('dat_ve_id');

            // Khôi phục ràng buộc unique cũ nếu cần
            $table->unique(['user_id', 'phim_id']);
        });
    }
};