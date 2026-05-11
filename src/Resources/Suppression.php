<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Suppression extends Resource
{
    /** @param array<string, mixed> $payload */
    public function add(array $payload): array
    {
        return $this->client->post('/v1/suppression', $payload) ?? [];
    }

    public function check(string $email): array
    {
        return $this->client->get('/v1/suppression/check', ['email' => $email]) ?? [];
    }

    public function remove(string $email): array
    {
        return $this->client->delete("/v1/suppression/" . rawurlencode($email)) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/suppression', $query) ?? [];
    }
}
