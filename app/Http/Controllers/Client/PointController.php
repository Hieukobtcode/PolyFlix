<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DatVe;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    public function updatePoints(Request $request)
    {
        try {
            // Log dữ liệu đầu vào
            Log::info('Request data: ', $request->all());

            $user = Auth::user();
            if (!$user) {
                Log::error('No authenticated user found.');
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa đăng nhập!'
                ], 401);
            }

            $pointsUsed = $request->input('points_used');
            $discount = $request->input('discount');
            $newTotal = $request->input('new_total');

            // Kiểm tra dữ liệu
            Log::info("Points used: $pointsUsed, Discount: $discount, New Total: $newTotal, User points: {$user->diem}");
            if ($pointsUsed > $user->diem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số điểm không đủ!'
                ], 400);
            }

            // Cập nhật điểm của người dùng
            $user->diem -= $pointsUsed;
            $user->save();
            Log::info("User points updated to: {$user->diem}");

            // Cập nhật trong bảng datves
            $datVe = DatVe::where('user_id', $user->id)
                         ->where('trang_thai', 'Chờ thanh toán')
                         ->first();

            if ($datVe) {
                $datVe->tong_tien = $newTotal;
                $datVe->save();
                Log::info("DatVe updated: tong_tien = $newTotal, khuyen_mai_id = $discount");
            } else {
                Log::warning("No pending DatVe found for user ID: {$user->id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn đặt vé đang xử lý!'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật điểm thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in updatePoints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra, vui lòng thử lại!'
            ], 500);
        }
    }
}