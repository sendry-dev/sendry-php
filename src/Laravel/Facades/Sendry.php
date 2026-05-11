<?php

declare(strict_types=1);

namespace Sendry\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Sendry\Resources\Analytics;
use Sendry\Resources\ApiKeys;
use Sendry\Resources\Audiences;
use Sendry\Resources\Billing;
use Sendry\Resources\Campaigns;
use Sendry\Resources\Contacts;
use Sendry\Resources\Domains;
use Sendry\Resources\Emails;
use Sendry\Resources\Suppression;
use Sendry\Resources\Team;
use Sendry\Resources\Templates;
use Sendry\Resources\Unsubscribes;
use Sendry\Resources\Webhooks;
use Sendry\Sendry as SendryClient;

/**
 * @method static Emails       emails()
 * @method static Domains      domains()
 * @method static Templates    templates()
 * @method static Contacts     contacts()
 * @method static Audiences    audiences()
 * @method static Campaigns    campaigns()
 * @method static Webhooks     webhooks()
 * @method static Analytics    analytics()
 * @method static Suppression  suppression()
 * @method static Unsubscribes unsubscribes()
 * @method static ApiKeys      apiKeys()
 * @method static Team         team()
 * @method static Billing      billing()
 * @method static bool         verifyWebhookSignature(string $payload, string $signature, string $secret)
 *
 * @see \Sendry\Sendry
 */
final class Sendry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SendryClient::class;
    }
}
