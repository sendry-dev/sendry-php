<?php

declare(strict_types=1);

namespace Sendry;

use Sendry\Resources\Analytics;
use Sendry\Resources\ApiKeys;
use Sendry\Resources\Audiences;
use Sendry\Resources\Automations;
use Sendry\Resources\Billing;
use Sendry\Resources\Campaigns;
use Sendry\Resources\Contacts;
use Sendry\Resources\DedicatedIps;
use Sendry\Resources\Deliverability;
use Sendry\Resources\Domains;
use Sendry\Resources\Emails;
use Sendry\Resources\Events;
use Sendry\Resources\Inbound;
use Sendry\Resources\NotificationPreferences;
use Sendry\Resources\Organizations;
use Sendry\Resources\Regions;
use Sendry\Resources\Status;
use Sendry\Resources\Suppression;
use Sendry\Resources\Team;
use Sendry\Resources\Templates;
use Sendry\Resources\TestEmails;
use Sendry\Resources\Unsubscribes;
use Sendry\Resources\Webhooks;

/**
 * Sendry — the official PHP client for the Sendry email API.
 *
 * Resources are exposed as both methods (`$sendry->emails()`) and properties
 * (`$sendry->emails`) so calling code can pick the style it prefers.
 *
 * @property-read Emails                  $emails
 * @property-read Domains                 $domains
 * @property-read Templates               $templates
 * @property-read Contacts                $contacts
 * @property-read Audiences               $audiences
 * @property-read Campaigns               $campaigns
 * @property-read Webhooks                $webhooks
 * @property-read Analytics               $analytics
 * @property-read Suppression             $suppression
 * @property-read Unsubscribes            $unsubscribes
 * @property-read ApiKeys                 $apiKeys
 * @property-read Team                    $team
 * @property-read Billing                 $billing
 * @property-read DedicatedIps            $dedicatedIps
 * @property-read Deliverability          $deliverability
 * @property-read Inbound                 $inbound
 * @property-read NotificationPreferences $notificationPreferences
 * @property-read Organizations           $organizations
 * @property-read Regions                 $regions
 * @property-read Status                  $status
 * @property-read TestEmails              $testEmails
 * @property-read Automations             $automations
 * @property-read Events                  $events
 */
final class Sendry
{
    public const VERSION = '0.2.0';

    private readonly HttpClient $client;
    /** @var array<string, object> */
    private array $resources = [];

    /**
     * @param array{
     *   base_url?: string,
     *   timeout?: float|int,
     *   retries?: int,
     *   headers?: array<string, string>,
     *   handler?: callable,
     * }|null $options
     */
    public function __construct(string $apiKey, ?array $options = null)
    {
        $this->client = new HttpClient($apiKey, $options ?? []);
    }

    public function emails(): Emails                                       { return $this->resource(Emails::class); }
    public function domains(): Domains                                     { return $this->resource(Domains::class); }
    public function templates(): Templates                                 { return $this->resource(Templates::class); }
    public function contacts(): Contacts                                   { return $this->resource(Contacts::class); }
    public function audiences(): Audiences                                 { return $this->resource(Audiences::class); }
    public function campaigns(): Campaigns                                 { return $this->resource(Campaigns::class); }
    public function webhooks(): Webhooks                                   { return $this->resource(Webhooks::class); }
    public function analytics(): Analytics                                 { return $this->resource(Analytics::class); }
    public function suppression(): Suppression                             { return $this->resource(Suppression::class); }
    public function unsubscribes(): Unsubscribes                           { return $this->resource(Unsubscribes::class); }
    public function apiKeys(): ApiKeys                                     { return $this->resource(ApiKeys::class); }
    public function team(): Team                                           { return $this->resource(Team::class); }
    public function billing(): Billing                                     { return $this->resource(Billing::class); }
    public function dedicatedIps(): DedicatedIps                           { return $this->resource(DedicatedIps::class); }
    public function deliverability(): Deliverability                       { return $this->resource(Deliverability::class); }
    public function inbound(): Inbound                                     { return $this->resource(Inbound::class); }
    public function notificationPreferences(): NotificationPreferences    { return $this->resource(NotificationPreferences::class); }
    public function organizations(): Organizations                         { return $this->resource(Organizations::class); }
    public function regions(): Regions                                     { return $this->resource(Regions::class); }
    public function status(): Status                                       { return $this->resource(Status::class); }
    public function testEmails(): TestEmails                               { return $this->resource(TestEmails::class); }
    public function automations(): Automations                             { return $this->resource(Automations::class); }
    public function events(): Events                                       { return $this->resource(Events::class); }

    public function __get(string $name): object
    {
        return match ($name) {
            'emails'                  => $this->emails(),
            'domains'                 => $this->domains(),
            'templates'               => $this->templates(),
            'contacts'                => $this->contacts(),
            'audiences'               => $this->audiences(),
            'campaigns'               => $this->campaigns(),
            'webhooks'                => $this->webhooks(),
            'analytics'               => $this->analytics(),
            'suppression'             => $this->suppression(),
            'unsubscribes'            => $this->unsubscribes(),
            'apiKeys'                 => $this->apiKeys(),
            'team'                    => $this->team(),
            'billing'                 => $this->billing(),
            'dedicatedIps'            => $this->dedicatedIps(),
            'deliverability'          => $this->deliverability(),
            'inbound'                 => $this->inbound(),
            'notificationPreferences' => $this->notificationPreferences(),
            'organizations'           => $this->organizations(),
            'regions'                 => $this->regions(),
            'status'                  => $this->status(),
            'testEmails'              => $this->testEmails(),
            'automations'             => $this->automations(),
            'events'                  => $this->events(),
            default                   => throw new \InvalidArgumentException("Unknown resource: {$name}"),
        };
    }

    /**
     * Verify a Sendry webhook signature using HMAC-SHA256 with constant-time
     * comparison. Returns true if the signature matches.
     */
    public static function verifyWebhookSignature(
        string $payload,
        string $signature,
        string $secret,
    ): bool {
        $expected = hash_hmac('sha256', $payload, $secret);
        $received = str_starts_with($signature, 'sha256=')
            ? substr($signature, 7)
            : $signature;

        return hash_equals($expected, $received);
    }

    /** @template T of object @param class-string<T> $class @return T */
    private function resource(string $class): object
    {
        /** @var T */
        return $this->resources[$class] ??= new $class($this->client);
    }
}
