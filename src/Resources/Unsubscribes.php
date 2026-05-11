<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Unsubscribes extends Resource
{
    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/unsubscribes', $query) ?? [];
    }
}
