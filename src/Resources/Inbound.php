<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Inbound extends Resource
{
    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/inbound', $query) ?? [];
    }

    public function get(string $inboundId): array
    {
        return $this->client->get("/v1/inbound/{$inboundId}") ?? [];
    }

    public function getConfig(): array
    {
        return $this->client->get('/v1/inbound/config') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function updateConfig(array $payload): array
    {
        return $this->client->put('/v1/inbound/config', $payload) ?? [];
    }
}
