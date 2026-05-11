<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class NotificationPreferences extends Resource
{
    public function get(): array
    {
        return $this->client->get('/v1/notification-preferences') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(array $payload): array
    {
        return $this->client->put('/v1/notification-preferences', $payload) ?? [];
    }
}
