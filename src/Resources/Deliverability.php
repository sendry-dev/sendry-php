<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Deliverability extends Resource
{
    /** @param array<string, mixed> $query */
    public function getReputation(array $query = []): array
    {
        return $this->client->get('/v1/deliverability/reputation', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getReputationHistory(string $domainId, array $query = []): array
    {
        return $this->client->get("/v1/deliverability/reputation/{$domainId}/history", $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getBlocklist(array $query = []): array
    {
        return $this->client->get('/v1/deliverability/blocklist', $query) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function runBlocklistCheck(array $payload): array
    {
        return $this->client->post('/v1/deliverability/blocklist/check', $payload) ?? [];
    }

    public function dismissAlert(string $alertId): array
    {
        return $this->client->patch(
            "/v1/deliverability/blocklist/alerts/{$alertId}",
            ['status' => 'dismissed'],
        ) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getReport(array $query = []): array
    {
        return $this->client->get('/v1/deliverability/report', $query) ?? [];
    }
}
