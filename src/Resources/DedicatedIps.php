<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class DedicatedIps extends Resource
{
    /** @param array<string, mixed> $payload */
    public function provision(array $payload = []): array
    {
        return $this->client->post('/v1/ips', $payload) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/ips', $query) ?? [];
    }

    public function get(string $ipId): array
    {
        return $this->client->get("/v1/ips/{$ipId}") ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function assign(string $ipId, array $payload): array
    {
        return $this->client->post("/v1/ips/{$ipId}/assign", $payload) ?? [];
    }

    public function removeAssignment(string $ipId, string $assignmentId): array
    {
        return $this->client->delete("/v1/ips/{$ipId}/assign/{$assignmentId}") ?? [];
    }

    public function release(string $ipId): array
    {
        return $this->client->delete("/v1/ips/{$ipId}") ?? [];
    }
}
