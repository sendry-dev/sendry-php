<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Templates extends Resource
{
    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/templates', $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(string $templateId, array $payload): array
    {
        return $this->client->patch("/v1/templates/{$templateId}", $payload) ?? [];
    }

    public function get(string $templateId): array
    {
        return $this->client->get("/v1/templates/{$templateId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/templates', $query) ?? [];
    }

    public function delete(string $templateId): array
    {
        return $this->client->delete("/v1/templates/{$templateId}") ?? [];
    }
}
