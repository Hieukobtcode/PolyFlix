<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GheNgoiSeeder extends Seeder
{
    public function run(): void
    {
        $phongChieus = DB::table('phong_chieus')->get();
        $loaiGhes = DB::table('loai_ghes')->get();
        
        if ($phongChieus->isEmpty() || $loaiGhes->isEmpty()) {
            $this->command->info('Không có dữ liệu phòng chiếu hoặc loại ghế để tạo ghế ngồi');
            return;
        }

        foreach ($phongChieus as $phongChieu) {
            // Tạo sơ đồ ghế cơ bản: 8 hàng x 12 cột
            $soHang = 8;
            $soCot = 12;
            
            for ($hang = 1; $hang <= $soHang; $hang++) {
                $hangChu = chr(64 + $hang); // A, B, C, D, E, F, G, H
                
                for ($cot = 1; $cot <= $soCot; $cot++) {
                    // Xác định loại ghế
                    $loaiGheId = 10; // Ghế thường mặc định
                    
                    // Hàng A, B: Ghế thường
                    if ($hang <= 2) {
                        $loaiGheId = 10; // Ghế thường
                    }
                    // Hàng C, D, E, F: Ghế VIP
                    elseif ($hang >= 3 && $hang <= 6) {
                        $loaiGheId = 11; // Ghế VIP
                    }
                    // Hàng G, H: Ghế đôi (chỉ cột chẵn)
                    elseif ($hang >= 7) {
                        if ($cot % 2 == 0) {
                            $loaiGheId = 12; // Ghế đôi
                        } else {
                            continue; // Bỏ qua cột lẻ cho ghế đôi
                        }
                    }
                    
                    $maGhe = $hangChu . str_pad($cot, 2, '0', STR_PAD_LEFT);
                    
                    DB::table('ghe_ngois')->insert([
                        'phong_chieu_id' => $phongChieu->id,
                        'loai_ghe' => $loaiGheId,
                        'hang' => $hangChu,
                        'cot' => $cot,
                        'ma_ghe' => $maGhe,
                        'trang_thai' => 'trong',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Đã tạo ghế ngồi cho tất cả phòng chiếu!');
    }
}
