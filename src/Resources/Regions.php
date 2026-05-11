<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Regions extends Resource
{
    public function list(): array
    {
        return $this->client->get('/v1/regions') ?? [];
    }

    public function getOrgSettings(): array
    {
        return $this->client->get('/v1/organizations/me/region') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function updateOrgSettings(array $payload): array
    {
        return $this->client->patch('/v1/organizations/me/region', $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function setDomainRegion(string $domainId, array $payload): array
    {
        return $this->client->patch("/v1/domains/{$domainId}/region", $payload) ?? [];
    }

    /** @param array<string, mixed> $query */
    public function getRegionAnalytics(array $query): array
    {
        return $this->client->get('/v1/analytics/regions', $query) ?? [];
    }
}
