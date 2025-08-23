<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\GheNgoiSuatChieu;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class SeatLockController extends Controller
{
    // lock seats (chọn)
    public function lock(Request $request)
    {
        $request->validate([
            'ghe_ids' => 'required|array|min:1',
            'ghe_ids.*' => 'integer',
            'suat_chieu_id' => 'required|integer|exists:suat_chieus,id',
        ]);

        $userId = $request->user()->id;
        $suatId = $request->suat_chieu_id;
        $inputSeatIds = array_unique($request->ghe_ids);

        // lock thời gian (phút)
        $LOCK_MINUTES = 5;
        $expiresAt = Carbon::now()->addMinutes($LOCK_MINUTES);

        // Cleanup ghế hết hạn trước khi kiểm tra
        $this->cleanupExpiredSeats();

        // 1. Kiểm tra xung đột: có ghế đã được đặt (da_dat) hay đang bị người khác giữ (da_chon & expires_at > now)
        $conflicts = GheNgoiSuatChieu::whereIn('ghe_ngoi_id', $inputSeatIds)
            ->where('suat_chieu_id', $suatId)
            ->where(function ($q) {
                $q->where('trang_thai', 'da_dat')
                    ->orWhere(function ($q2) {
                        $q2->where('trang_thai', 'da_chon')
                            ->where('expires_at', '>', now());
                    });
            })
            ->get();

        // phân loại conflict theo user & trạng thái
        $conflictBooked = $conflicts->where('trang_thai', 'da_dat');
        $conflictLockedByOther = $conflicts->where('trang_thai', 'da_chon')->filter(function ($r) use ($userId) {
            return $r->user_id != $userId && (!$r->expires_at || $r->expires_at->isFuture());
        });

        if ($conflictBooked->isNotEmpty()) {
            $ids = $conflictBooked->pluck('ghe_ngoi_id')->toArray();
            return response()->json([
                'success' => false,
                'type' => 'booked',
                'ghe_ids' => $ids,
                'message' => 'Một hoặc nhiều ghế đã được đặt.'
            ], 409);
        }

        if ($conflictLockedByOther->isNotEmpty()) {
            $ids = $conflictLockedByOther->pluck('ghe_ngoi_id')->toArray();
            return response()->json([
                'success' => false,
                'type' => 'locked',
                'ghe_ids' => $ids,
                'message' => 'Một hoặc nhiều ghế đang được người khác giữ.'
            ], 409);
        }

        // 2. Thực hiện lock (updateOrCreate)
        DB::transaction(function () use ($inputSeatIds, $suatId, $userId, $expiresAt) {
            foreach ($inputSeatIds as $gheId) {
                GheNgoiSuatChieu::updateOrCreate(
                    ['ghe_ngoi_id' => $gheId, 'suat_chieu_id' => $suatId],
                    ['trang_thai' => 'da_chon', 'user_id' => $userId, 'expires_at' => $expiresAt]
                );
            }
        });

        return response()->json([
            'success' => true,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);
    }

    // unlock seats (hủy chọn)
    public function unlock(Request $request)
    {
        $request->validate([
            'ghe_ids' => 'required|array|min:1',
            'suat_chieu_id' => 'required|integer|exists:suat_chieus,id',
        ]);

        $userId = $request->user()->id;
        $ids = array_unique($request->ghe_ids);
        GheNgoiSuatChieu::whereIn('ghe_ngoi_id', $ids)
            ->where('suat_chieu_id', $request->suat_chieu_id)
            ->where('user_id', $userId) // chỉ hủy của chính user
            ->update(['trang_thai' => 'trong', 'expires_at' => null, 'user_id' => null]);

        return response()->json(['success' => true]);
    }

    // heartbeat - kéo dài expires_at cho những ghế user đang giữ
    public function heartbeat(Request $request)
    {
        $request->validate([
            'ghe_ids' => 'required|array|min:1',
            'suat_chieu_id' => 'required|integer|exists:suat_chieus,id',
        ]);

        $userId = $request->user()->id;
        $ids = array_unique($request->ghe_ids);
        $LOCK_MINUTES = 5;
        $newExpires = Carbon::now()->addMinutes($LOCK_MINUTES);

        GheNgoiSuatChieu::whereIn('ghe_ngoi_id', $ids)
            ->where('suat_chieu_id', $request->suat_chieu_id)
            ->where('user_id', $userId)
            ->update(['expires_at' => $newExpires]);

        return response()->json(['success' => true, 'expires_at' => $newExpires->toDateTimeString()]);
    }

    /**
     * Cleanup ghế hết hạn
     */
    private function cleanupExpiredSeats()
    {
        GheNgoiSuatChieu::where('trang_thai', 'da_chon')
            ->where('expires_at', '<', Carbon::now())
            ->update([
                'trang_thai' => 'trong',
                'user_id' => null,
                'expires_at' => null
            ]);
    }
}
