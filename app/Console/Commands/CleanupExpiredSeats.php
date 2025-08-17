<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GheNgoiSuatChieu;
use Carbon\Carbon;

class CleanupExpiredSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired seat locks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired seat locks...');

        // Tìm và xóa các ghế đã hết hạn
        $expiredSeats = GheNgoiSuatChieu::where('trang_thai', 'da_chon')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        $count = $expiredSeats->count();

        if ($count > 0) {
            // Cập nhật trạng thái về 'trong' và xóa thông tin user
            GheNgoiSuatChieu::where('trang_thai', 'da_chon')
                ->where('expires_at', '<', Carbon::now())
                ->update([
                    'trang_thai' => 'trong',
                    'user_id' => null,
                    'expires_at' => null
                ]);

            $this->info("Cleaned up {$count} expired seat locks.");
        } else {
            $this->info('No expired seat locks found.');
        }

        return 0;
    }
}
