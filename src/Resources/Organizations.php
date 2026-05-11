<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Organizations extends Resource
{
    public function getCurrent(): array
    {
        return $this->client->get('/v1/organizations/me') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(array $payload): array
    {
        return $this->client->patch('/v1/organizations/me', $payload) ?? [];
    }

    public function getBranding(): array
    {
        return $this->client->get('/v1/organizations/me/branding') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function updateBranding(array $payload): array
    {
        return $this->client->patch('/v1/organizations/me/branding', $payload) ?? [];
    }
}
