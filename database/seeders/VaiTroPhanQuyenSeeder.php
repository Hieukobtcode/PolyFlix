<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaiTroPhanQuyenSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminId = 1;
        $adminChiNhanhId = 2;
        $adminRapId = 3;

        $fullPermissionIds = range(1, 197);

        $chiNhanhPermissionIds = [
            193,194,195,197,48,87,88,89,90,91,92,93,94,95,96,97,98,99,100,
            108,109,110,111,112,113,114,115,116,117,118,119,120,121,28,
            31,32,33,150,151,152,153,143,144,145,146,147,148,174,177,136,137,138,139,140,141,142 
        ];

        $rapPermissionIds = [
            193,194,195,197,90,95,96,97,98,99,100,136,
            137,138,139,140,141,142,150,153,143,146,174,177,28

        ];

        foreach ($fullPermissionIds as $permissionId) {
            DB::table('vai_tro_phan_quyens')->insert([
                'vai_tro_id' => $superAdminId,
                'phan_quyen_id' => $permissionId,
            ]);
        }

        foreach ($chiNhanhPermissionIds as $permissionId) {
            DB::table('vai_tro_phan_quyens')->insert([
                'vai_tro_id' => $adminChiNhanhId,
                'phan_quyen_id' => $permissionId,
            ]);
        }

        foreach ($rapPermissionIds as $permissionId) {
            DB::table('vai_tro_phan_quyens')->insert([
                'vai_tro_id' => $adminRapId,
                'phan_quyen_id' => $permissionId,
            ]);
        }
    }
}
