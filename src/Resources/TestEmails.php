<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class TestEmails extends Resource
{
    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/test-emails', $query) ?? [];
    }

    public function get(string $testEmailId): array
    {
        return $this->client->get("/v1/test-emails/{$testEmailId}") ?? [];
    }
}
