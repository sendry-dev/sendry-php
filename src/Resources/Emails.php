<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Emails extends Resource
{
    /** @param array<string, mixed> $payload */
    public function send(array $payload): array
    {
        return $this->client->post('/v1/emails', $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function sendBatch(array $payload): array
    {
        return $this->client->post('/v1/emails/batch', $payload) ?? [];
    }

    public function get(string $emailId): array
    {
        return $this->client->get("/v1/emails/{$emailId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/emails', $query) ?? [];
    }
}
