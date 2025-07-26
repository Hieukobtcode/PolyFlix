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
            $message = $request->input('message');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Bạn là trợ lý đặt vé xem phim của PolyFlix.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            // Debug: kiểm tra toàn bộ JSON response
            return response()->json($response->json());

            return response()->json($response->json());

            return response()->json(['reply' => $reply]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
