<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Notifier
{
    public static function critical(string $message, array $context = []): void
    {
        // log locally
        Log::critical($message, $context);

        // if slack is configured, send critical message to slack channel
        if (env('LOG_SLACK_WEBHOOK_URL')) {
            try {
                Log::channel('slack')->critical($message, $context);
            } catch (\Throwable $e) {
                Log::error('Failed to send slack notification: ' . $e->getMessage());
            }
        }
    }
}
