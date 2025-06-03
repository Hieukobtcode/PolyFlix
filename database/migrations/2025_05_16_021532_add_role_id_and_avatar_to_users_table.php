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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('vai_tro_id')->nullable()->after('id');
            $table->string('avatar')->nullable()->after('email');

            // Thiết lập khóa ngoại tới bảng roles
            $table->foreign('vai_tro_id')
                  ->references('id')
                  ->on('vai_tros')
                  ->onDelete('set null'); // Khi xóa role, role_id sẽ thành null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['vai_tro_id']);
            $table->dropColumn(['vai_tro_id', 'avatar']);
        });
    }
};
