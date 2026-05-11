<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Contacts extends Resource
{
    /** @param array<string, mixed> $payload */
    public function upsert(array $payload): array
    {
        return $this->client->post('/v1/contacts', $payload) ?? [];
    }

    public function get(string $contactId): array
    {
        return $this->client->get("/v1/contacts/{$contactId}") ?? [];
    }

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/contacts', $query) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(string $contactId, array $payload): array
    {
        return $this->client->patch("/v1/contacts/{$contactId}", $payload) ?? [];
    }

    public function delete(string $contactId): array
    {
        return $this->client->delete("/v1/contacts/{$contactId}") ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function bulkImport(array $payload): array
    {
        return $this->client->post('/v1/contacts/bulk-import', $payload) ?? [];
    }
}
