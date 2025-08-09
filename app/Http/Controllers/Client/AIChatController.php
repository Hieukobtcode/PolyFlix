<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            if (session('is_waiting_ai', false)) {
                return response()->json([
                    'error' => true,
                    'message' => '🕐 Vui lòng chờ AI trả lời xong rồi hãy gửi tiếp.'
                ], 429);
            }

            // 🚫 Đánh dấu đang chờ AI trả lời
            session(['is_waiting_ai' => true]);

            $userMessage = $request->input('message');
            $history = session('chat_history', []);

            $history[] = [
                'role' => 'user',
                'parts' => [['text' => $userMessage]],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),
                ['contents' => $history]
            );

            if ($response->successful()) {
                $data = $response->json();
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi từ AI.';

                // ➕ Format lại HTML cho đẹp
                $htmlReply = $this->formatReply($replyText);

                $history[] = [
                    'role' => 'model',
                    'parts' => [['text' => $replyText]],
                ];

                session([
                    'chat_history' => $history,
                    'is_waiting_ai' => false,
                ]);

                return response()->json([
                    'reply' => $htmlReply,
                    'history' => $history
                ]);
            }

            session(['is_waiting_ai' => false]);

            return response()->json([
                'error' => true,
                'message' => '❌ Gemini trả về lỗi.',
                'details' => $response->json()
            ], $response->status());
        } catch (\Throwable $e) {
            session(['is_waiting_ai' => false]);

            return response()->json([
                'error' => true,
                'message' => '🚨 Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reset()
    {
        session()->forget('chat_history');
        return response()->json(['message' => '✅ Đã xóa lịch sử chat.']);
    }

    /**
     * Format lại câu trả lời dạng HTML (simple markdown hỗ trợ *bold*, xuống dòng)
     */
    private function formatReply(string $text): string
    {
        // Escape HTML để tránh XSS
        $text = e($text);

        // **bold**
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

        // Bỏ các bullet ở đầu dòng: "* ", "- ", "• "
        $text = preg_replace('/^\s*[\-\*\x{2022}]\s+/mu', '', $text);

        // Chuẩn hoá xuống dòng liên tiếp còn 1 dòng trống
        $text = preg_replace("/(\r?\n){2,}/", "\n\n", $text);

        // Chia đoạn bằng 1 dòng trống, rồi đổi \n trong mỗi đoạn thành <br>
        $parts = preg_split("/\r?\n\r?\n/", $text);
        $parts = array_map(function ($p) {
            return '<p>' . nl2br($p) . '</p>';
        }, $parts);

        return implode('', $parts);
    }
}