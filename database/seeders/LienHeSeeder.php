<?php

namespace Database\Seeders;

use App\Models\LienHe;
use App\Models\LienHeActivityLog;
use App\Models\LienHeNote;
use Illuminate\Database\Seeder;

class LienHeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lienHes = [
            [
                'ten' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@gmail.com',
                'so_dien_thoai' => '0987654321',
                'noi_dung' => 'Tôi muốn biết thêm thông tin về sản phẩm của bạn.',
                'trang_thai' => true,
                'muc_do_uu_tien' => 'binh_thuong',
                'nguon_goc' => 'Website',
                'phan_loai' => 'Thông tin sản phẩm',
                'ghi_chu_noi_bo' => 'Khách hàng cần thông tin chi tiết về sản phẩm mới.',
                'nguoi_phu_trach' => 'Nguyễn Văn X',
                'da_phan_hoi' => true,
                'create_at' => now()->subDays(10),
                'update_at' => now()->subDays(9),
            ],
            [
                'ten' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'so_dien_thoai' => '0912345678',
                'noi_dung' => 'Làm thế nào để đặt hàng trực tuyến?',
                'trang_thai' => false,
                'muc_do_uu_tien' => 'cao',
                'nguon_goc' => 'Facebook',
                'phan_loai' => 'Hướng dẫn mua hàng',
                'ghi_chu_noi_bo' => 'Khách hàng mới, cần hướng dẫn chi tiết.',
                'nguoi_phu_trach' => 'Trần Thị Y',
                'da_phan_hoi' => false,
                'create_at' => now()->subDays(8),
                'update_at' => now()->subDays(8),
            ],
            [
                'ten' => 'Lê Văn C',
                'email' => 'levanc@gmail.com',
                'so_dien_thoai' => '0978123456',
                'noi_dung' => 'Tôi cần hỗ trợ kỹ thuật cho sản phẩm đã mua.',
                'trang_thai' => false,
                'muc_do_uu_tien' => 'cao',
                'nguon_goc' => 'Email',
                'phan_loai' => 'Hỗ trợ kỹ thuật',
                'ghi_chu_noi_bo' => 'Sản phẩm gặp lỗi, cần hỗ trợ gấp.',
                'nguoi_phu_trach' => 'Lê Văn Z',
                'da_phan_hoi' => false,
                'create_at' => now()->subDays(7),
                'update_at' => now()->subDays(6),
            ],
            [
                'ten' => 'Phạm Thị D',
                'email' => 'phamthid@gmail.com',
                'so_dien_thoai' => '0965432109',
                'noi_dung' => 'Tôi muốn phản hồi về chất lượng dịch vụ khách hàng.',
                'trang_thai' => true,
                'muc_do_uu_tien' => 'binh_thuong',
                'nguon_goc' => 'Website',
                'phan_loai' => 'Phản hồi dịch vụ',
                'ghi_chu_noi_bo' => 'Khách hàng đánh giá tốt về dịch vụ.',
                'nguoi_phu_trach' => 'Phạm Văn W',
                'da_phan_hoi' => true,
                'create_at' => now()->subDays(6),
                'update_at' => now()->subDays(5),
            ],
            [
                'ten' => 'Hoàng Văn E',
                'email' => 'hoangvane@gmail.com',
                'so_dien_thoai' => '0932109876',
                'noi_dung' => 'Tôi có thể trả lại sản phẩm đã mua không?',
                'trang_thai' => false,
                'muc_do_uu_tien' => 'cao',
                'nguon_goc' => 'Hotline',
                'phan_loai' => 'Đổi trả sản phẩm',
                'ghi_chu_noi_bo' => 'Khách hàng muốn trả lại sản phẩm vì không phù hợp.',
                'nguoi_phu_trach' => 'Hoàng Thị V',
                'da_phan_hoi' => false,
                'create_at' => now()->subDays(5),
                'update_at' => now()->subDays(5),
            ],
            [
                'ten' => 'Ngô Thị F',
                'email' => 'ngothif@gmail.com',
                'so_dien_thoai' => '0943210987',
                'noi_dung' => 'Tôi muốn biết thêm về chính sách bảo hành.',
                'trang_thai' => true,
                'muc_do_uu_tien' => 'thap',
                'nguon_goc' => 'Website',
                'phan_loai' => 'Chính sách bảo hành',
                'ghi_chu_noi_bo' => 'Đã gửi thông tin chính sách bảo hành cho khách.',
                'nguoi_phu_trach' => 'Ngô Văn U',
                'da_phan_hoi' => true,
                'create_at' => now()->subDays(4),
                'update_at' => now()->subDays(3),
            ],
            [
                'ten' => 'Đỗ Văn G',
                'email' => 'dovang@gmail.com',
                'so_dien_thoai' => '0954321098',
                'noi_dung' => 'Sản phẩm của tôi bị lỗi, tôi muốn đổi sản phẩm mới.',
                'trang_thai' => false,
                'muc_do_uu_tien' => 'cao',
                'nguon_goc' => 'Email',
                'phan_loai' => 'Bảo hành sản phẩm',
                'ghi_chu_noi_bo' => 'Sản phẩm lỗi trong thời gian bảo hành, cần xử lý gấp.',
                'nguoi_phu_trach' => 'Đỗ Thị T',
                'da_phan_hoi' => false,
                'create_at' => now()->subDays(3),
                'update_at' => now()->subDays(2),
            ],
            [
                'ten' => 'Vũ Thị H',
                'email' => 'vuthih@gmail.com',
                'so_dien_thoai' => '0965432109',
                'noi_dung' => 'Tôi muốn hủy đơn hàng đã đặt.',
                'trang_thai' => true,
                'muc_do_uu_tien' => 'cao',
                'nguon_goc' => 'Hotline',
                'phan_loai' => 'Hủy đơn hàng',
                'ghi_chu_noi_bo' => 'Đã hủy đơn hàng và hoàn tiền cho khách.',
                'nguoi_phu_trach' => 'Vũ Văn S',
                'da_phan_hoi' => true,
                'create_at' => now()->subDays(2),
                'update_at' => now()->subDays(1),
            ],
            [
                'ten' => 'Đinh Văn I',
                'email' => 'dinhvani@gmail.com',
                'so_dien_thoai' => '0976543210',
                'noi_dung' => 'Tôi cần thông tin về thời gian giao hàng.',
                'trang_thai' => false,
                'muc_do_uu_tien' => 'binh_thuong',
                'nguon_goc' => 'Website',
                'phan_loai' => 'Thông tin giao hàng',
                'ghi_chu_noi_bo' => 'Khách hàng cần biết thời gian giao hàng chính xác.',
                'nguoi_phu_trach' => 'Đinh Thị R',
                'da_phan_hoi' => false,
                'create_at' => now()->subDays(1),
                'update_at' => now()->subDays(1),
            ],
            [
                'ten' => 'Bùi Thị K',
                'email' => 'buithik@gmail.com',
                'so_dien_thoai' => '0987654321',
                'noi_dung' => 'Tôi muốn đăng ký nhận thông tin khuyến mãi.',
                'trang_thai' => true,
                'muc_do_uu_tien' => 'thap',
                'nguon_goc' => 'Facebook',
                'phan_loai' => 'Đăng ký khuyến mãi',
                'ghi_chu_noi_bo' => 'Đã thêm khách hàng vào danh sách nhận thông tin khuyến mãi.',
                'nguoi_phu_trach' => 'Bùi Văn Q',
                'da_phan_hoi' => true,
                'create_at' => now(),
                'update_at' => now(),
            ],
        ];

        // Tạo dữ liệu liên hệ
        foreach ($lienHes as $lienHe) {
            $contact = LienHe::create($lienHe);

            // Tạo log hoạt động
            LienHeActivityLog::create([
                'lien_he_id' => $contact->id,
                'hanh_dong' => 'create',
                'mo_ta' => 'Tạo mới liên hệ',
                'nguoi_thuc_hien' => 'Hệ thống',
                'du_lieu_moi' => $lienHe,
                'created_at' => $lienHe['create_at'],
                'updated_at' => $lienHe['create_at'],
            ]);

            // Tạo ghi chú
            LienHeNote::create([
                'lien_he_id' => $contact->id,
                'noi_dung' => 'Ghi chú ban đầu: ' . $lienHe['ghi_chu_noi_bo'],
                'nguoi_tao' => 'Hệ thống',
                'created_at' => $lienHe['create_at'],
                'updated_at' => $lienHe['create_at'],
            ]);

            // Nếu đã xử lý, tạo thêm log hoạt động
            if ($lienHe['trang_thai']) {
                LienHeActivityLog::create([
                    'lien_he_id' => $contact->id,
                    'hanh_dong' => 'update_status',
                    'mo_ta' => 'Cập nhật trạng thái liên hệ',
                    'nguoi_thuc_hien' => $lienHe['nguoi_phu_trach'],
                    'du_lieu_cu' => ['trang_thai' => false],
                    'du_lieu_moi' => ['trang_thai' => true],
                    'created_at' => $lienHe['update_at'],
                    'updated_at' => $lienHe['update_at'],
                ]);

                // Thêm ghi chú về việc xử lý
                LienHeNote::create([
                    'lien_he_id' => $contact->id,
                    'noi_dung' => 'Đã xử lý liên hệ này.',
                    'nguoi_tao' => $lienHe['nguoi_phu_trach'],
                    'created_at' => $lienHe['update_at'],
                    'updated_at' => $lienHe['update_at'],
                ]);
            }

            // Nếu đã phản hồi, tạo thêm log hoạt động
            if ($lienHe['da_phan_hoi']) {
                LienHeActivityLog::create([
                    'lien_he_id' => $contact->id,
                    'hanh_dong' => 'send_email',
                    'mo_ta' => 'Gửi email phản hồi',
                    'nguoi_thuc_hien' => $lienHe['nguoi_phu_trach'],
                    'du_lieu_moi' => [
                        'subject' => 'Phản hồi liên hệ của bạn',
                        'message' => 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi đã nhận được thông tin và xin phản hồi như sau...',
                    ],
                    'created_at' => $lienHe['update_at'],
                    'updated_at' => $lienHe['update_at'],
                ]);

                // Thêm ghi chú về việc phản hồi
                LienHeNote::create([
                    'lien_he_id' => $contact->id,
                    'noi_dung' => 'Đã gửi email phản hồi cho khách hàng.',
                    'nguoi_tao' => $lienHe['nguoi_phu_trach'],
                    'created_at' => $lienHe['update_at'],
                    'updated_at' => $lienHe['update_at'],
                ]);
            }
        }
    }
}
