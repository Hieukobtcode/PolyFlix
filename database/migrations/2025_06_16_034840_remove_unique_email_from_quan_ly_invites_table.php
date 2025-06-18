<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueEmailFromQuanLyInvitesTable extends Migration
{
    public function up()
    {
        Schema::table('quan_ly_invites', function (Blueprint $table) {
            $table->dropUnique(['email']); // Xóa unique constraint trên email
        });
    }

    public function down()
    {
        Schema::table('quan_ly_invites', function (Blueprint $table) {
            $table->unique('email'); // Thêm lại nếu rollback
        });
    }
}
