<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE phong_chieus MODIFY COLUMN status ENUM('hoat_dong', 'tam_dung', 'bao_tri') DEFAULT 'hoat_dong'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE phong_chieus MODIFY COLUMN status ENUM('hoat_dong', 'tam_dung', 'bao_tri', 'da_dong') DEFAULT 'hoat_dong'");
    }
};
