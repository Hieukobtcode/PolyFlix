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
  Schema::table('dat_ves', function (Blueprint $table) {
    $table->unsignedBigInteger('phim_id')->after('ngay_cap_nhat');

});
    
}

public function down(): void
{
    Schema::table('dat_ves', function (Blueprint $table) {
        $table->dropColumn('phim');
    });
}
};
