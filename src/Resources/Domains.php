<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Domains extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/domains', $payload) ?? [];
    }

    public function get(string $domainId): array
    {
        return $this->client->get("/v1/domains/{$domainId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/domains', $query) ?? [];
    }

    public function verify(string $domainId): array
    {
        return $this->client->post("/v1/domains/{$domainId}/verify") ?? [];
    }

    public function delete(string $domainId): array
    {
        return $this->client->delete("/v1/domains/{$domainId}") ?? [];
    }
}
