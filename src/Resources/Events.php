<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Events extends Resource
{
    /** @param array<string, mixed> $payload */
    public function ingest(array $payload): array
    {
        return $this->client->post('/v1/events', $payload) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/events', $query) ?? [];
    }

    public function get(string $eventId): array
    {
        return $this->client->get("/v1/events/{$eventId}") ?? [];
    }
}
