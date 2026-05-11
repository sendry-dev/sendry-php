<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Analytics extends Resource
{
    /** @param array<string, mixed> $query */
    public function overview(array $query = []): array
    {
        return $this->client->get('/v1/analytics/overview', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function breakdown(array $query = []): array
    {
        return $this->client->get('/v1/analytics/breakdown', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function cohort(array $query = []): array
    {
        return $this->client->get('/v1/analytics/cohort', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function benchmark(array $query = []): array
    {
        return $this->client->get('/v1/analytics/benchmark', $query) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function comparison(array $query = []): array
    {
        return $this->client->get('/v1/analytics/comparison', $query) ?? [];
    }
}
