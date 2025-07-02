<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FreshSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fresh-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables and run fresh seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting fresh seed process...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // List of tables to truncate in order (respecting foreign keys)
        $tables = [
            'lich_su_su_dung_khuyen_mais',
            'khuyen_mai_chi_nhanhs',
            'chi_tiet_dich_vus',
            'chi_tiet_dat_ves',
            'dat_ve_do_an',
            'dat_ve_combo',
            'dat_ves',
            'ratings',
            'comments',
            'ghe_ngois',
            'suat_chieus',
            'phong_chieus',
            'so_do_ghes',
            'phim_phu_des',
            'phim_dinh_dangs',
            'phim_the_loais',
            'phim_raps',
            'phim_chi_nhanhs',
            'combo_do_ans',
            'combos',
            'chi_nhanh_combo',
            'do_ans',
            'chi_nhanh_do_an',
            'danh_muc_do_ans',
            'vai_tro_phan_quyens',
            'admin_requests',
            'quan_ly_invites',
            'users',
            'rap_phims',
            'chi_nhanhs',
            'banners',
            'bai_viets',
            'khuyen_mais',
            'cap_bac_thes',
            'loai_ghes',
            'loai_phongs',
            'phu_de_phims',
            'dinh_dang_phims',
            'phims',
            'the_loai_phims',
            'phan_quyens',
            'vai_tros',
            'cau_hinhs',
        ];

        $this->info('🗑️  Truncating tables...');
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->line("   ✓ Truncated: {$table}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('🌱 Running seeders...');
        $this->call('db:seed');

        $this->info('✅ Fresh seed completed successfully!');

        return 0;
    }
}
