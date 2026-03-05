<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function verifyWebhook(Request $request): Response
    {
        $mode = $request->query('hub.mode');
        $token = $request->query('hub.verify_token');
        $challenge = $request->query('hub.challenge');

        if ($mode === 'subscribe' && $token === env('VERIFY_TOKEN')) {
            return response($challenge, Response::HTTP_OK)
                ->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', Response::HTTP_FORBIDDEN);
    }

    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('WhatsApp webhook payload received', $payload);

        $message = data_get($payload, 'entry.0.changes.0.value.messages.0', []);
        $from = data_get($message, 'from');
        $text = data_get($message, 'text.body');
        $timestamp = data_get($message, 'timestamp');

        if ($from && $text) {
            $redisMessage = [
                'from' => $from,
                'message' => $text,
                'timestamp' => (string) $timestamp,
            ];

            Redis::rpush('whatsapp_incoming_messages', json_encode($redisMessage, JSON_UNESCAPED_UNICODE));

            Log::info('WhatsApp message received', [
                'from' => $from,
                'text' => $text,
                'timestamp' => $timestamp,
            ]);
        }

        return response()->json(['status' => 'received'], Response::HTTP_OK);
    }
}
