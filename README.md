# Sendry PHP SDK

The official PHP client for the [Sendry](https://sendry.online) email API, with first-class Laravel support.

[![Packagist Version](https://img.shields.io/packagist/v/sendry/sendry.svg)](https://packagist.org/packages/sendry/sendry)
[![PHP Version](https://img.shields.io/packagist/php-v/sendry/sendry.svg)](https://packagist.org/packages/sendry/sendry)
[![License](https://img.shields.io/packagist/l/sendry/sendry.svg)](LICENSE)

## Installation

```bash
composer require sendry/sendry
```

Requires PHP 8.1+. Works in plain PHP, Symfony, and Laravel 10 / 11 / 12.

## Quick start (plain PHP)

```php
use Sendry\Sendry;

$sendry = new Sendry(getenv('SENDRY_API_KEY'));

$response = $sendry->emails()->send([
    'from'    => 'hello@yourdomain.com',
    'to'      => 'user@example.com',
    'subject' => 'Welcome',
    'html'    => '<p>Thanks for signing up.</p>',
    'text'    => 'Thanks for signing up.',
]);

echo $response['id']; // em_abc123
```

Both `$sendry->emails()` and `$sendry->emails` work — pick whichever you prefer.

## Laravel

The package is auto-discovered. Just publish the config and add your API key.

```bash
php artisan vendor:publish --tag=sendry-config
```

`.env`:

```
SENDRY_API_KEY=sn_live_your_api_key_here
```

Use the facade:

```php
use Sendry\Laravel\Facades\Sendry;

Sendry::emails()->send([
    'from'    => 'hello@yourdomain.com',
    'to'      => $user->email,
    'subject' => 'Welcome, '.$user->name,
    'html'    => view('emails.welcome', ['user' => $user])->render(),
]);
```

Or resolve from the container:

```php
public function handle(\Sendry\Sendry $sendry)
{
    $sendry->emails()->send([...]);
}
```

## Resources

```
$sendry->emails()        // send, sendBatch, get, list
$sendry->domains()       // create, verify, list, delete
$sendry->templates()     // create, update, get, list, delete
$sendry->contacts()      // upsert, get, list, update, delete, bulkImport
$sendry->audiences()     // create, get, list, update, delete
$sendry->campaigns()     // create, get, list, update, schedule, send, delete
$sendry->webhooks()      // create, get, list, delete
$sendry->analytics()     // overview, breakdown, cohort, benchmark, comparison
$sendry->suppression()   // add, check, remove, list
$sendry->unsubscribes()  // list
$sendry->apiKeys()       // create, list, delete
$sendry->team()          // invite, list, updateRole, remove
$sendry->billing()       // overview, createCheckout, createPortal
```

## Error handling

```php
use Sendry\Exceptions\{
    SendryException,
    AuthenticationException,
    ValidationException,
    RateLimitException,
    NotFoundException,
    ApiException,
    NetworkException,
};

try {
    $sendry->emails()->send([...]);
} catch (RateLimitException $e) {
    sleep(max($e->retryAfter, 1));
    // retry...
} catch (ValidationException $e) {
    // $e->details holds the field-level errors
    Log::warning('Sendry validation failed', (array) $e->details);
} catch (AuthenticationException $e) {
    abort(500, 'Invalid Sendry API key — check SENDRY_API_KEY');
} catch (NetworkException $e) {
    // DNS / connection / timeout — safe to retry
} catch (ApiException $e) {
    // Any other API error (5xx after retries, etc.)
    report($e);
}
```

## Webhook signature verification

```php
use Sendry\Sendry;

Route::post('/webhooks/sendry', function (Request $request) {
    $valid = Sendry::verifyWebhookSignature(
        $request->getContent(),
        $request->header('X-Sendry-Signature'),
        config('sendry.webhook_secret'),
    );

    abort_unless($valid, 401);

    // Process event...
});
```

`verifyWebhookSignature` uses constant-time HMAC-SHA256 comparison.

## Testing

```bash
composer install
composer test
```

The suite mocks HTTP via Guzzle's `MockHandler` — no network access required.

## License

MIT — see [LICENSE](LICENSE).
