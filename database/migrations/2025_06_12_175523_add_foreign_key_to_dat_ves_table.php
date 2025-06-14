<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('dat_ves')
            ->whereNotIn('phim_id', function ($query) {
                $query->select('id')->from('phims');
            })
            ->delete();

        Schema::table('dat_ves', function (Blueprint $table) {
            $table->foreign('phim_id')
                  ->references('id')->on('phims')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dat_ves', function (Blueprint $table) {
            $table->dropForeign(['phim_id']);
        });
    }
};
