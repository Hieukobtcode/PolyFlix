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
        Schema::table('ghe_ngoi_suat_chieu', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ghe_ngoi_suat_chieu', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
