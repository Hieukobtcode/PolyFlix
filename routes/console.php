<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Đăng ký command cleanup ghế hết hạn
Artisan::command('seats:cleanup-expired', function () {
    $this->info('Starting cleanup of expired seat locks...');

    $expiredSeats = \App\Models\GheNgoiSuatChieu::where('trang_thai', 'da_chon')
        ->where('expires_at', '<', now())
        ->get();

    $count = $expiredSeats->count();

    if ($count > 0) {
        \App\Models\GheNgoiSuatChieu::where('trang_thai', 'da_chon')
            ->where('expires_at', '<', now())
            ->update([
                'trang_thai' => 'trong',
                'user_id' => null,
                'expires_at' => null
            ]);

        $this->info("Cleaned up {$count} expired seat locks.");
    } else {
        $this->info('No expired seat locks found.');
    }
})->purpose('Cleanup expired seat locks');

// Lên lịch chạy cleanup mỗi phút
Schedule::command('seats:cleanup-expired')->everyMinute();
