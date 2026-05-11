<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Status extends Resource
{
    public function getCurrent(): array
    {
        return $this->client->get('/v1/status') ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getHistory(array $query = []): array
    {
        return $this->client->get('/v1/status/history', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getLatency(array $query = []): array
    {
        return $this->client->get('/v1/status/latency', $query) ?? [];
    }
}
