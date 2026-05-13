# Changelog

All notable changes to `sendry/sendry` (PHP) are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **Symfony Mailer / Laravel Mail transport** — `Sendry\Symfony\SendryTransport` + `SendryTransportFactory` for the `sendry://` DSN. The Laravel service provider auto-registers `'transport' => 'sendry'` so apps can use the Mail facade end-to-end (`Mail::to($user)->send(new WelcomeMail($user))`) without writing a custom transport. Closes the gap where `docs.sendry.online/sdks/php` documented a mail driver that wasn't actually shipped.

### Notes

- `symfony/mailer` is a `suggest` dep (only required if you opt into the transport). Laravel apps already ship it transitively.

## [0.1.0] - 2026-05-11

### Added

- Initial public release.
- `Sendry\Sendry` client with resource accessors as both methods and properties.
- Resource classes: Emails, Domains, Templates, Contacts, Audiences,
  Campaigns, Webhooks, Analytics, Suppression, Unsubscribes, ApiKeys,
  Team, Billing.
- Typed exception hierarchy: `SendryException` → `ApiException` →
  `AuthenticationException`, `ValidationException`, `RateLimitException`,
  `NotFoundException`. `NetworkException` for transport failures.
- Automatic retries on 5xx / network errors with exponential backoff.
- `Sendry::verifyWebhookSignature(...)` — constant-time HMAC-SHA256.
- Laravel integration: `SendryServiceProvider` (auto-discovered),
  `Sendry\Laravel\Facades\Sendry` facade, publishable `config/sendry.php`.
- PHPUnit test suite with Guzzle MockHandler.
