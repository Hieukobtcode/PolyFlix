<?php

use App\Models\KhuyenMai;
use App\Models\User;

test('khuyen mai model works', function () {
    $khuyenMai = KhuyenMai::create([
        'ma_khuyen_mai' => 'TEST123',
        'ten' => 'Test Khuyến Mãi',
        'mo_ta' => 'Mô tả test',
        'loai_giam_gia' => 'phan_tram',
        'gia_tri_giam' => 10.00,
        'giam_toi_da' => 50000.00,
        'ap_dung_cho' => 've',
        'don_toi_thieu' => 100000.00,
        'ngay_bat_dau' => now(),
        'ngay_ket_thuc' => now()->addDays(30),
        'so_lan_su_dung_toi_da' => 100,
        'so_lan_da_su_dung' => 0,
        'trang_thai' => 'hoat_dong'
    ]);

    expect($khuyenMai->ma_khuyen_mai)->toBe('TEST123');
    expect($khuyenMai->ten)->toBe('Test Khuyến Mãi');
});

test('client promotions page accessible', function () {
    $response = $this->get('/promotions');
    $response->assertStatus(200);
});

test('admin khuyen mai requires auth', function () {
    $response = $this->get('/admin/khuyen-mai');
    $response->assertRedirect('/login');
});

test('admin khuyen mai accessible when authenticated', function () {
    $user = User::factory()->create([
        'vai_tro' => 'admin'
    ]);

    $response = $this->actingAs($user)->get('/admin/khuyen-mai');
    $response->assertStatus(200);
});
