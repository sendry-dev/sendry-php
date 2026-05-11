<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Campaigns extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/campaigns', $payload) ?? [];
    }

    public function get(string $campaignId): array
    {
        return $this->client->get("/v1/campaigns/{$campaignId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/campaigns', $query) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(string $campaignId, array $payload): array
    {
        return $this->client->patch("/v1/campaigns/{$campaignId}", $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function schedule(string $campaignId, array $payload): array
    {
        return $this->client->post("/v1/campaigns/{$campaignId}/schedule", $payload) ?? [];
    }

    public function send(string $campaignId): array
    {
        return $this->client->post("/v1/campaigns/{$campaignId}/send") ?? [];
    }

    public function delete(string $campaignId): array
    {
        return $this->client->delete("/v1/campaigns/{$campaignId}") ?? [];
    }
}
