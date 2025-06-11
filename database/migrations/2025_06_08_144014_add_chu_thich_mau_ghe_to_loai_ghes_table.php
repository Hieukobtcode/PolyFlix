<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->string('chu_thich_mau_ghe')->nullable()->after('ten_loai_ghe'); // hoặc thay 'ten_loai_ghe' bằng cột hiện có
        });
    }

    public function down(): void
    {
        Schema::table('loai_ghes', function (Blueprint $table) {
            $table->dropColumn('chu_thich_mau_ghe');
        });
    }
};
