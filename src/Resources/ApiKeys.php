<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class ApiKeys extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/api-keys', $payload) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/api-keys', $query) ?? [];
    }

    public function delete(string $apiKeyId): array
    {
        return $this->client->delete("/v1/api-keys/{$apiKeyId}") ?? [];
    }
}
