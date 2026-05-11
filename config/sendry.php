<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | API key
    |--------------------------------------------------------------------------
    | Your Sendry API key, generated from the dashboard. Live keys start with
    | `sn_live_`, test keys with `sn_test_`.
    */
    'api_key' => env('SENDRY_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    | Override only when running against a self-hosted Sendry instance or a
    | local development server. Defaults to the production endpoint.
    */
    'base_url' => env('SENDRY_BASE_URL', 'https://api.sendry.online'),

    /*
    |--------------------------------------------------------------------------
    | Request timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('SENDRY_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry count
    |--------------------------------------------------------------------------
    | Number of retries on 5xx or network errors. Exponential backoff is
    | applied automatically (200ms, 400ms, 800ms…).
    */
    'retries' => env('SENDRY_RETRIES', 2),

    /*
    |--------------------------------------------------------------------------
    | Webhook secret
    |--------------------------------------------------------------------------
    | The shared secret used to sign incoming webhook payloads. Pass this to
    | `Sendry::verifyWebhookSignature(...)` to validate requests.
    */
    'webhook_secret' => env('SENDRY_WEBHOOK_SECRET'),
];
