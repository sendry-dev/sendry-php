<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Team extends Resource
{
    /** @param array<string, mixed> $payload */
    public function invite(array $payload): array
    {
        return $this->client->post('/v1/team/invitations', $payload) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/team/members', $query) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function updateRole(string $memberId, array $payload): array
    {
        return $this->client->patch("/v1/team/members/{$memberId}", $payload) ?? [];
    }

    public function remove(string $memberId): array
    {
        return $this->client->delete("/v1/team/members/{$memberId}") ?? [];
    }
}
