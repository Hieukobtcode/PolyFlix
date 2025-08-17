<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\GheNgoiSuatChieu;

class SeatLockController extends Controller
{
    /**
     * Khóa ghế cho user
     */
    public function lock(Request $request)
    {
        $request->validate([
            'ghe_ngoi_id' => 'required|integer',
            'suat_chieu_id' => 'required|integer'
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        try {
            $gheNgoiSuatChieu = GheNgoiSuatChieu::updateOrCreate(
                [
                    'ghe_ngoi_id' => $request->ghe_ngoi_id,
                    'suat_chieu_id' => $request->suat_chieu_id
                ],
                [
                    'trang_thai' => 'da_chon',
                    'user_id' => Auth::id(),
                    'expires_at' => now()->addMinutes(10) // Khóa trong 10 phút
                ]
            );

            return response()->json(['success' => true, 'message' => 'Đã khóa ghế']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi khóa ghế: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    /**
     * Mở khóa ghế
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'ghe_ngoi_id' => 'required|integer',
            'suat_chieu_id' => 'required|integer'
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        try {
            GheNgoiSuatChieu::where('ghe_ngoi_id', $request->ghe_ngoi_id)
                ->where('suat_chieu_id', $request->suat_chieu_id)
                ->where('user_id', Auth::id())
                ->update([
                    'trang_thai' => 'trong',
                    'user_id' => null,
                    'expires_at' => null
                ]);

            return response()->json(['success' => true, 'message' => 'Đã mở khóa ghế']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi mở khóa ghế: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }

    /**
     * Heartbeat để duy trì khóa ghế
     */
    public function heartbeat(Request $request)
    {
        $request->validate([
            'ghe_ngoi_ids' => 'required|array',
            'suat_chieu_id' => 'required|integer'
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        try {
            GheNgoiSuatChieu::whereIn('ghe_ngoi_id', $request->ghe_ngoi_ids)
                ->where('suat_chieu_id', $request->suat_chieu_id)
                ->where('user_id', Auth::id())
                ->update([
                    'expires_at' => now()->addMinutes(10) // Gia hạn thêm 10 phút
                ]);

            return response()->json(['success' => true, 'message' => 'Đã gia hạn khóa ghế']);
        } catch (\Exception $e) {
            Log::error('Lỗi khi gia hạn khóa ghế: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
    }
}
