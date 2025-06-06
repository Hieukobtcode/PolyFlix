<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTenSoDoFromSoDoGhesTable extends Migration
{
    public function up(): void
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->dropColumn('ten_so_do');
        });
    }

    public function down(): void
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->string('ten_so_do')->nullable(); // hoặc bỏ nullable nếu bạn muốn giữ nguyên cũ
        });
    }
}
