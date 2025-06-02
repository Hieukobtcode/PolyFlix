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
        Schema::create('khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->string('ma_khuyen_mai');
            $table->string('ten');
            $table->text('mo_ta');
            $table->enum('loai_giam_gia', ['phan_tram', 'tien']);
            $table->decimal('gia_tri_giam', 10, 2);
            $table->decimal('giam_toi_da', 10, 2)->nullable();
            $table->enum('ap_dung_cho', ['ve', 'do_an', 'tat_ca']);
            $table->decimal('don_toi_thieu', 10, 2)->nullable();
            $table->dateTime('ngay_bat_dau');
            $table->dateTime('ngay_ket_thuc');
            $table->integer('so_lan_su_dung_toi_da')->nullable();
            $table->integer('so_lan_da_su_dung')->default(0);
            $table->enum('trang_thai', ['hoat_dong', 'tam_dung']);
            $table->timestamp('create_at')->useCurrent();
            $table->timestamp('update_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mais');
    }
};
