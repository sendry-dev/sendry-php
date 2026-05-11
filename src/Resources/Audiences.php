<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Audiences extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/audiences', $payload) ?? [];
    }

    public function get(string $audienceId): array
    {
        return $this->client->get("/v1/audiences/{$audienceId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/audiences', $query) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(string $audienceId, array $payload): array
    {
        return $this->client->patch("/v1/audiences/{$audienceId}", $payload) ?? [];
    }

    public function delete(string $audienceId): array
    {
        return $this->client->delete("/v1/audiences/{$audienceId}") ?? [];
    }
}
