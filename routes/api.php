<?php

use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/webhook', [WhatsAppWebhookController::class, 'verifyWebhook']);
Route::post('/webhook', [WhatsAppWebhookController::class, 'handleWebhook']);
