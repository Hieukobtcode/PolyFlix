<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            $table->dropColumn(['email', 'so_dien_thoai']);
        });
    }

    public function down(): void
    {
        Schema::table('rap_phims', function (Blueprint $table) {
            $table->string('email')->unique()->nullable();
            $table->text('so_dien_thoai')->nullable();
        });
    }
};
