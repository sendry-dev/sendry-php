<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Webhooks extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/webhooks', $payload) ?? [];
    }

    public function get(string $webhookId): array
    {
        return $this->client->get("/v1/webhooks/{$webhookId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/webhooks', $query) ?? [];
    }

    public function delete(string $webhookId): array
    {
        return $this->client->delete("/v1/webhooks/{$webhookId}") ?? [];
    }
}
