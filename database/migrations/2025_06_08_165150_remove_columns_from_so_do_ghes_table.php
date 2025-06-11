<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromSoDoGhesTable extends Migration
{
    public function up(): void
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->dropColumn(['so_hang_thuong', 'so_hang_vip', 'so_hang_doi', 'mo_ta']);
        });
    }

    public function down(): void
    {
        Schema::table('so_do_ghes', function (Blueprint $table) {
            $table->integer('so_hang_thuong')->nullable();
            $table->integer('so_hang_vip')->nullable();
            $table->integer('so_hang_doi')->nullable();
            $table->text('mo_ta')->nullable();
        });
    }
}
