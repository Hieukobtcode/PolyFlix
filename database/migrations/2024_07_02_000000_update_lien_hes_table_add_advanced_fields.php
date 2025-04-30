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
        Schema::table('lien_hes', function (Blueprint $table) {
            $table->enum('muc_do_uu_tien', ['cao', 'binh_thuong', 'thap'])->default('binh_thuong')->after('trang_thai');
            $table->string('nguon_goc', 100)->nullable()->after('muc_do_uu_tien');
            $table->string('phan_loai', 100)->nullable()->after('nguon_goc');
            $table->text('ghi_chu_noi_bo')->nullable()->after('phan_loai');
            $table->string('nguoi_phu_trach', 100)->nullable()->after('ghi_chu_noi_bo');
            $table->timestamp('ngay_hen')->nullable()->after('nguoi_phu_trach');
            $table->boolean('da_phan_hoi')->default(false)->after('ngay_hen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lien_hes', function (Blueprint $table) {
            $table->dropColumn([
                'muc_do_uu_tien',
                'nguon_goc',
                'phan_loai',
                'ghi_chu_noi_bo',
                'nguoi_phu_trach',
                'ngay_hen',
                'da_phan_hoi',
            ]);
        });
    }
};
