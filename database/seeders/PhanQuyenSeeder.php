<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhanQuyenSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $now = Carbon::now()->toDateTimeString();

        $permissions = [

            ['id' => 1, 'ten' => 'Xem danh sách liên hệ', 'slug' => 'admin.lien-he.index'],
            ['id' => 2, 'ten' => 'Tạo liên hệ', 'slug' => 'admin.lien-he.create'],
            ['id' => 3, 'ten' => 'Lưu liên hệ', 'slug' => 'admin.lien-he.store'],
            ['id' => 4, 'ten' => 'Xem chi tiết liên hệ', 'slug' => 'admin.lien-he.show'],
            ['id' => 5, 'ten' => 'Sửa liên hệ', 'slug' => 'admin.lien-he.edit'],
            ['id' => 6, 'ten' => 'Cập nhật liên hệ', 'slug' => 'admin.lien-he.update'],
            ['id' => 7, 'ten' => 'Xóa liên hệ', 'slug' => 'admin.lien-he.destroy'],
            ['id' => 8, 'ten' => 'Xem bảng điều khiển liên hệ', 'slug' => 'admin.lien-he.dashboard'],
            ['id' => 9, 'ten' => 'Xuất dữ liệu liên hệ', 'slug' => 'admin.lien-he.export'],
            ['id' => 10, 'ten' => 'Thêm ghi chú liên hệ', 'slug' => 'admin.lien-he.add-note'],
            ['id' => 11, 'ten' => 'Cập nhật trạng thái liên hệ', 'slug' => 'admin.lien-he.update-status'],
            ['id' => 12, 'ten' => 'Gửi email liên hệ', 'slug' => 'admin.lien-he.send-email'],
            ['id' => 13, 'ten' => 'Thao tác hàng loạt liên hệ', 'slug' => 'admin.lien-he.bulk-action'],
            ['id' => 14, 'ten' => 'Xem danh sách thể loại phim', 'slug' => 'admin.the-loai-phim.index'],
            ['id' => 15, 'ten' => 'Tạo thể loại phim', 'slug' => 'admin.the-loai-phim.create'],
            ['id' => 16, 'ten' => 'Lưu thể loại phim', 'slug' => 'admin.the-loai-phim.store'],
            ['id' => 17, 'ten' => 'Xem thể loại phim', 'slug' => 'admin.the-loai-phim.show'],
            ['id' => 18, 'ten' => 'Sửa thể loại phim', 'slug' => 'admin.the-loai-phim.edit'],
            ['id' => 19, 'ten' => 'Cập nhật thể loại phim', 'slug' => 'admin.the-loai-phim.update'],
            ['id' => 20, 'ten' => 'Xóa thể loại phim', 'slug' => 'admin.the-loai-phim.destroy'],
            ['id' => 21, 'ten' => 'Xem danh sách định dạng phim', 'slug' => 'admin.dinh-dang-phim.index'],
            ['id' => 22, 'ten' => 'Tạo định dạng phim', 'slug' => 'admin.dinh-dang-phim.create'],
            ['id' => 23, 'ten' => 'Lưu định dạng phim', 'slug' => 'admin.dinh-dang-phim.store'],
            ['id' => 24, 'ten' => 'Xem định dạng phim', 'slug' => 'admin.dinh-dang-phim.show'],
            ['id' => 25, 'ten' => 'Sửa định dạng phim', 'slug' => 'admin.dinh-dang-phim.edit'],
            ['id' => 26, 'ten' => 'Cập nhật định dạng phim', 'slug' => 'admin.dinh-dang-phim.update'],
            ['id' => 27, 'ten' => 'Xóa định dạng phim', 'slug' => 'admin.dinh-dang-phim.destroy'],
            ['id' => 28, 'ten' => 'Xem danh sách phim', 'slug' => 'admin.phim.index'],
            ['id' => 29, 'ten' => 'Tạo phim', 'slug' => 'admin.phim.create'],
            ['id' => 30, 'ten' => 'Lưu phim', 'slug' => 'admin.phim.store'],
            ['id' => 31, 'ten' => 'Xem phim', 'slug' => 'admin.phim.show'],
            ['id' => 32, 'ten' => 'Sửa phim', 'slug' => 'admin.phim.edit'],
            ['id' => 33, 'ten' => 'Cập nhật phim', 'slug' => 'admin.phim.update'],
            ['id' => 34, 'ten' => 'Xóa phim', 'slug' => 'admin.phim.destroy'],
            ['id' => 35, 'ten' => 'Xem thùng rác phim', 'slug' => 'admin.phim.trash'],
            ['id' => 36, 'ten' => 'Khôi phục phim', 'slug' => 'admin.phim.restore'],
            ['id' => 37, 'ten' => 'Xóa vĩnh viễn phim', 'slug' => 'admin.phim.force-delete'],
            ['id' => 38, 'ten' => 'Xem danh sách bai viet', 'slug' => 'admin.bai-viet.index'],
            ['id' => 39, 'ten' => 'Tạo bai viet', 'slug' => 'admin.bai-viet.create'],
            ['id' => 40, 'ten' => 'Lưu bai viet', 'slug' => 'admin.bai-viet.store'],
            ['id' => 41, 'ten' => 'Xem bai viet', 'slug' => 'admin.bai-viet.show'],
            ['id' => 42, 'ten' => 'Sửa bai viet', 'slug' => 'admin.bai-viet.edit'],
            ['id' => 43, 'ten' => 'Cập nhật bai viet', 'slug' => 'admin.bai-viet.update'],
            ['id' => 44, 'ten' => 'Xóa bai viet', 'slug' => 'admin.bai-viet.destroy'],
            ['id' => 45, 'ten' => 'Xem danh sách chi nhanh', 'slug' => 'admin.chi-nhanh.index'],
            ['id' => 46, 'ten' => 'Tạo chi nhanh', 'slug' => 'admin.chi-nhanh.create'],
            ['id' => 47, 'ten' => 'Lưu chi nhanh', 'slug' => 'admin.chi-nhanh.store'],
            ['id' => 48, 'ten' => 'Xem chi nhanh', 'slug' => 'admin.chi-nhanh.show'],
            ['id' => 49, 'ten' => 'Sửa chi nhanh', 'slug' => 'admin.chi-nhanh.edit'],
            ['id' => 50, 'ten' => 'Cập nhật chi nhanh', 'slug' => 'admin.chi-nhanh.update'],
            ['id' => 51, 'ten' => 'Xóa chi nhanh', 'slug' => 'admin.chi-nhanh.destroy'],
            ['id' => 52, 'ten' => 'Xem danh sách vai tro', 'slug' => 'admin.vai-tro.index'],
            ['id' => 53, 'ten' => 'Tạo vai tro', 'slug' => 'admin.vai-tro.create'],
            ['id' => 54, 'ten' => 'Lưu vai tro', 'slug' => 'admin.vai-tro.store'],
            ['id' => 55, 'ten' => 'Xem vai tro', 'slug' => 'admin.vai-tro.show'],
            ['id' => 56, 'ten' => 'Sửa vai tro', 'slug' => 'admin.vai-tro.edit'],
            ['id' => 57, 'ten' => 'Cập nhật vai tro', 'slug' => 'admin.vai-tro.update'],
            ['id' => 58, 'ten' => 'Xóa vai tro', 'slug' => 'admin.vai-tro.destroy'],
            ['id' => 59, 'ten' => 'Xem danh sách phan quyen', 'slug' => 'admin.phan-quyen.index'],
            ['id' => 60, 'ten' => 'Tạo phan quyen', 'slug' => 'admin.phan-quyen.create'],
            ['id' => 61, 'ten' => 'Lưu phan quyen', 'slug' => 'admin.phan-quyen.store'],
            ['id' => 62, 'ten' => 'Xem phan quyen', 'slug' => 'admin.phan-quyen.show'],
            ['id' => 63, 'ten' => 'Sửa phan quyen', 'slug' => 'admin.phan-quyen.edit'],
            ['id' => 64, 'ten' => 'Cập nhật phan quyen', 'slug' => 'admin.phan-quyen.update'],
            ['id' => 65, 'ten' => 'Xóa phan quyen', 'slug' => 'admin.phan-quyen.destroy'],
            ['id' => 66, 'ten' => 'Xem danh sách users', 'slug' => 'admin.users.index'],
            ['id' => 67, 'ten' => 'Tạo users', 'slug' => 'admin.users.create'],
            ['id' => 68, 'ten' => 'Lưu users', 'slug' => 'admin.users.store'],
            ['id' => 69, 'ten' => 'Xem users', 'slug' => 'admin.users.show'],
            ['id' => 70, 'ten' => 'Sửa users', 'slug' => 'admin.users.edit'],
            ['id' => 71, 'ten' => 'Cập nhật users', 'slug' => 'admin.users.update'],
            ['id' => 72, 'ten' => 'Xóa users', 'slug' => 'admin.users.destroy'],
            ['id' => 73, 'ten' => 'Xem danh sách banners', 'slug' => 'admin.banners.index'],
            ['id' => 74, 'ten' => 'Tạo banners', 'slug' => 'admin.banners.create'],
            ['id' => 75, 'ten' => 'Lưu banners', 'slug' => 'admin.banners.store'],
            ['id' => 76, 'ten' => 'Xem banners', 'slug' => 'admin.banners.show'],
            ['id' => 77, 'ten' => 'Sửa banners', 'slug' => 'admin.banners.edit'],
            ['id' => 78, 'ten' => 'Cập nhật banners', 'slug' => 'admin.banners.update'],
            ['id' => 79, 'ten' => 'Xóa banners', 'slug' => 'admin.banners.destroy'],
            ['id' => 80, 'ten' => 'Xem danh sách loai phong', 'slug' => 'admin.loai-phong.index'],
            ['id' => 81, 'ten' => 'Tạo loai phong', 'slug' => 'admin.loai-phong.create'],
            ['id' => 82, 'ten' => 'Lưu loai phong', 'slug' => 'admin.loai-phong.store'],
            ['id' => 83, 'ten' => 'Xem loai phong', 'slug' => 'admin.loai-phong.show'],
            ['id' => 84, 'ten' => 'Sửa loai phong', 'slug' => 'admin.loai-phong.edit'],
            ['id' => 85, 'ten' => 'Cập nhật loai phong', 'slug' => 'admin.loai-phong.update'],
            ['id' => 86, 'ten' => 'Xóa loai phong', 'slug' => 'admin.loai-phong.destroy'],
            ['id' => 87, 'ten' => 'Xem danh sách rap phim', 'slug' => 'admin.rap-phim.index'],
            ['id' => 88, 'ten' => 'Tạo rap phim', 'slug' => 'admin.rap-phim.create'],
            ['id' => 89, 'ten' => 'Lưu rap phim', 'slug' => 'admin.rap-phim.store'],
            ['id' => 90, 'ten' => 'Xem rap phim', 'slug' => 'admin.rap-phim.show'],
            ['id' => 91, 'ten' => 'Sửa rap phim', 'slug' => 'admin.rap-phim.edit'],
            ['id' => 92, 'ten' => 'Cập nhật rap phim', 'slug' => 'admin.rap-phim.update'],
            ['id' => 93, 'ten' => 'Xóa rap phim', 'slug' => 'admin.rap-phim.destroy'],
            ['id' => 94, 'ten' => 'Xem danh sách phong chieu', 'slug' => 'admin.phong-chieu.index'],
            ['id' => 95, 'ten' => 'Tạo phong chieu', 'slug' => 'admin.phong-chieu.create'],
            ['id' => 96, 'ten' => 'Lưu phong chieu', 'slug' => 'admin.phong-chieu.store'],
            ['id' => 97, 'ten' => 'Xem phong chieu', 'slug' => 'admin.phong-chieu.show'],
            ['id' => 98, 'ten' => 'Sửa phong chieu', 'slug' => 'admin.phong-chieu.edit'],
            ['id' => 99, 'ten' => 'Cập nhật phong chieu', 'slug' => 'admin.phong-chieu.update'],
            ['id' => 100, 'ten' => 'Xóa phong chieu', 'slug' => 'admin.phong-chieu.destroy'],
            ['id' => 101, 'ten' => 'Xem danh sách loai ghe', 'slug' => 'admin.loai-ghe.index'],
            ['id' => 102, 'ten' => 'Tạo loai ghe', 'slug' => 'admin.loai-ghe.create'],
            ['id' => 103, 'ten' => 'Lưu loai ghe', 'slug' => 'admin.loai-ghe.store'],
            ['id' => 104, 'ten' => 'Xem loai ghe', 'slug' => 'admin.loai-ghe.show'],
            ['id' => 105, 'ten' => 'Sửa loai ghe', 'slug' => 'admin.loai-ghe.edit'],
            ['id' => 106, 'ten' => 'Cập nhật loai ghe', 'slug' => 'admin.loai-ghe.update'],
            ['id' => 107, 'ten' => 'Xóa loai ghe', 'slug' => 'admin.loai-ghe.destroy'],
            ['id' => 108, 'ten' => 'Xem danh sách so do ghe', 'slug' => 'admin.so-do-ghe.index'],
            ['id' => 109, 'ten' => 'Tạo so do ghe', 'slug' => 'admin.so-do-ghe.create'],
            ['id' => 110, 'ten' => 'Lưu so do ghe', 'slug' => 'admin.so-do-ghe.store'],
            ['id' => 111, 'ten' => 'Xem so do ghe', 'slug' => 'admin.so-do-ghe.show'],
            ['id' => 112, 'ten' => 'Sửa so do ghe', 'slug' => 'admin.so-do-ghe.edit'],
            ['id' => 113, 'ten' => 'Cập nhật so do ghe', 'slug' => 'admin.so-do-ghe.update'],
            ['id' => 114, 'ten' => 'Xóa so do ghe', 'slug' => 'admin.so-do-ghe.destroy'],
            ['id' => 115, 'ten' => 'Xem danh sách ghe ngoi', 'slug' => 'admin.ghe-ngoi.index'],
            ['id' => 116, 'ten' => 'Tạo ghe ngoi', 'slug' => 'admin.ghe-ngoi.create'],
            ['id' => 117, 'ten' => 'Lưu ghe ngoi', 'slug' => 'admin.ghe-ngoi.store'],
            ['id' => 118, 'ten' => 'Xem ghe ngoi', 'slug' => 'admin.ghe-ngoi.show'],
            ['id' => 119, 'ten' => 'Sửa ghe ngoi', 'slug' => 'admin.ghe-ngoi.edit'],
            ['id' => 120, 'ten' => 'Cập nhật ghe ngoi', 'slug' => 'admin.ghe-ngoi.update'],
            ['id' => 121, 'ten' => 'Xóa ghe ngoi', 'slug' => 'admin.ghe-ngoi.destroy'],
            ['id' => 122, 'ten' => 'Xem danh sách cap bac the', 'slug' => 'admin.cap-bac-the.index'],
            ['id' => 123, 'ten' => 'Tạo cap bac the', 'slug' => 'admin.cap-bac-the.create'],
            ['id' => 124, 'ten' => 'Lưu cap bac the', 'slug' => 'admin.cap-bac-the.store'],
            ['id' => 125, 'ten' => 'Xem cap bac the', 'slug' => 'admin.cap-bac-the.show'],
            ['id' => 126, 'ten' => 'Sửa cap bac the', 'slug' => 'admin.cap-bac-the.edit'],
            ['id' => 127, 'ten' => 'Cập nhật cap bac the', 'slug' => 'admin.cap-bac-the.update'],
            ['id' => 128, 'ten' => 'Xóa cap bac the', 'slug' => 'admin.cap-bac-the.destroy'],
            ['id' => 129, 'ten' => 'Xem danh sách khuyen mai', 'slug' => 'admin.khuyen-mai.index'],
            ['id' => 130, 'ten' => 'Tạo khuyen mai', 'slug' => 'admin.khuyen-mai.create'],
            ['id' => 131, 'ten' => 'Lưu khuyen mai', 'slug' => 'admin.khuyen-mai.store'],
            ['id' => 132, 'ten' => 'Xem khuyen mai', 'slug' => 'admin.khuyen-mai.show'],
            ['id' => 133, 'ten' => 'Sửa khuyen mai', 'slug' => 'admin.khuyen-mai.edit'],
            ['id' => 134, 'ten' => 'Cập nhật khuyen mai', 'slug' => 'admin.khuyen-mai.update'],
            ['id' => 135, 'ten' => 'Xóa khuyen mai', 'slug' => 'admin.khuyen-mai.destroy'],
            ['id' => 136, 'ten' => 'Xem danh sách suat chieu', 'slug' => 'admin.suat-chieu.index'],
            ['id' => 137, 'ten' => 'Tạo suat chieu', 'slug' => 'admin.suat-chieu.create'],
            ['id' => 138, 'ten' => 'Lưu suat chieu', 'slug' => 'admin.suat-chieu.store'],
            ['id' => 139, 'ten' => 'Xem suat chieu', 'slug' => 'admin.suat-chieu.show'],
            ['id' => 140, 'ten' => 'Sửa suat chieu', 'slug' => 'admin.suat-chieu.edit'],
            ['id' => 141, 'ten' => 'Cập nhật suat chieu', 'slug' => 'admin.suat-chieu.update'],
            ['id' => 142, 'ten' => 'Xóa suat chieu', 'slug' => 'admin.suat-chieu.destroy'],
            ['id' => 143, 'ten' => 'Xem danh sách combos', 'slug' => 'admin.combos.index'],
            ['id' => 144, 'ten' => 'Tạo combos', 'slug' => 'admin.combos.create'],
            ['id' => 145, 'ten' => 'Lưu combos', 'slug' => 'admin.combos.store'],
            ['id' => 146, 'ten' => 'Xem combos', 'slug' => 'admin.combos.show'],
            ['id' => 147, 'ten' => 'Sửa combos', 'slug' => 'admin.combos.edit'],
            ['id' => 148, 'ten' => 'Cập nhật combos', 'slug' => 'admin.combos.update'],
            ['id' => 149, 'ten' => 'Xóa combos', 'slug' => 'admin.combos.destroy'],
            ['id' => 150, 'ten' => 'Xem danh sách do an', 'slug' => 'admin.do-an.index'],
            ['id' => 151, 'ten' => 'Tạo do an', 'slug' => 'admin.do-an.create'],
            ['id' => 152, 'ten' => 'Lưu do an', 'slug' => 'admin.do-an.store'],
            ['id' => 153, 'ten' => 'Xem do an', 'slug' => 'admin.do-an.show'],
            ['id' => 154, 'ten' => 'Sửa do an', 'slug' => 'admin.do-an.edit'],
            ['id' => 155, 'ten' => 'Cập nhật do an', 'slug' => 'admin.do-an.update'],
            ['id' => 156, 'ten' => 'Xóa do an', 'slug' => 'admin.do-an.destroy'],
            ['id' => 157, 'ten' => 'Xem danh sách danh muc do an', 'slug' => 'admin.danh-muc-do-an.index'],
            ['id' => 158, 'ten' => 'Tạo danh muc do an', 'slug' => 'admin.danh-muc-do-an.create'],
            ['id' => 159, 'ten' => 'Lưu danh muc do an', 'slug' => 'admin.danh-muc-do-an.store'],
            ['id' => 160, 'ten' => 'Xem danh muc do an', 'slug' => 'admin.danh-muc-do-an.show'],
            ['id' => 161, 'ten' => 'Sửa danh muc do an', 'slug' => 'admin.danh-muc-do-an.edit'],
            ['id' => 162, 'ten' => 'Cập nhật danh muc do an', 'slug' => 'admin.danh-muc-do-an.update'],
            ['id' => 163, 'ten' => 'Xóa danh muc do an', 'slug' => 'admin.danh-muc-do-an.destroy'],
            ['id' => 164, 'ten' => 'Xem cấu hình', 'slug' => 'admin.cau-hinh.index'],
            ['id' => 165, 'ten' => 'Sửa cấu hình', 'slug' => 'admin.cau-hinh.edit'],
            ['id' => 166, 'ten' => 'Cập nhật cấu hình', 'slug' => 'admin.cau-hinh.update'],
            ['id' => 167, 'ten' => 'Cập nhật sơ đồ ghế ngồi', 'slug' => 'admin.ghe-ngoi.updateSeat'],
            ['id' => 168, 'ten' => 'Đặt cấp bậc thẻ mặc định', 'slug' => 'admin.cap-bac-the.set-default'],
            ['id' => 169, 'ten' => 'Gán khuyến mãi cho chi nhánh', 'slug' => 'admin.khuyen-mai.assign-chi-nhanh'],
            ['id' => 170, 'ten' => 'Xem thống kê sử dụng khuyến mãi', 'slug' => 'admin.khuyen-mai.thong-ke-su-dung'],





            ['id' => 171, 'ten' => 'Xem yêu cầu quản lý', 'slug' => 'admin.requests.index'],
            ['id' => 172, 'ten' => 'Phê duyệt yêu cầu', 'slug' => 'admin.requests.approve'],
            ['id' => 173, 'ten' => 'Từ chối yêu cầu', 'slug' => 'admin.requests.reject'],

            ['id' => 174, 'ten' => 'Xem danh sách vé đã đặt', 'slug' => 'admin.dat-ves.index'],
            ['id' => 175, 'ten' => 'Thêm vé đặt', 'slug' => 'admin.dat-ves.create'],
            ['id' => 176, 'ten' => 'Lưu vé đặt', 'slug' => 'admin.dat-ves.store'],
            ['id' => 177, 'ten' => 'Xem chi tiết vé đặt', 'slug' => 'admin.dat-ves.show'],
            ['id' => 178, 'ten' => 'Sửa vé đặt', 'slug' => 'admin.dat-ves.edit'],
            ['id' => 179, 'ten' => 'Cập nhật vé đặt', 'slug' => 'admin.dat-ves.update'],
            ['id' => 180, 'ten' => 'Xóa vé đặt', 'slug' => 'admin.dat-ves.destroy'],

            ['id' => 181, 'ten' => 'Xem bảng giá vé', 'slug' => 'admin.gia-ve.index'],
            ['id' => 182, 'ten' => 'Cập nhật giá vé', 'slug' => 'admin.gia-ve.cap-nhat'],

            ['id' => 183, 'ten' => 'Xem danh sách bình luận', 'slug' => 'admin.comments.index'],
            ['id' => 184, 'ten' => 'Xem bình luận theo phim', 'slug' => 'admin.comments.show'],
            ['id' => 185, 'ten' => 'Phản hồi bình luận', 'slug' => 'admin.comments.reply'],
            ['id' => 186, 'ten' => 'Ẩn bình luận', 'slug' => 'admin.comments.hide'],
            ['id' => 187, 'ten' => 'Hiện lại bình luận', 'slug' => 'admin.comments.unhide'],
            ['id' => 188, 'ten' => 'Xóa bình luận', 'slug' => 'admin.comments.destroy'],

            ['id' => 189, 'ten' => 'Hủy lời mời quản lý', 'slug' => 'admin.invite.cancel'],


            ['id' => 190, 'ten' => 'Xóa nhiều suất chiếu', 'slug' => 'admin.suat-chieu.bulk-delete'],
            ['id' => 191, 'ten' => 'Chuyển trạng thái nhiều suất chiếu', 'slug' => 'admin.suat-chieu.bulk-toggle-status'],
            ['id' => 192, 'ten' => 'Chuyển trạng thái suất chiếu', 'slug' => 'admin.suat-chieu.toggle-status'],



            ['id' => 193, 'ten' => 'Xem tổng quan thống kê', 'slug' => 'admin.thong-ke.index'],
            ['id' => 194, 'ten' => 'Xem biểu đồ thống kê', 'slug' => 'admin.thong-ke.dashboard'],
            ['id' => 195, 'ten' => 'Xem thống kê phim', 'slug' => 'admin.thong-ke.phim'],
            ['id' => 196, 'ten' => 'Xem thống kê liên hệ', 'slug' => 'admin.thong-ke.lien-he'],
            ['id' => 197, 'ten' => 'Xuất báo cáo thống kê', 'slug' => 'admin.thong-ke.xuat-bao-cao'],





        ];

        foreach ($permissions as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('phan_quyens')->truncate();
        DB::table('phan_quyens')->insert($permissions);

        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
