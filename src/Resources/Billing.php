<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Billing extends Resource
{
    public function overview(): array
    {
        return $this->client->get('/v1/billing/overview') ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function createCheckout(array $payload): array
    {
        return $this->client->post('/v1/billing/checkout', $payload) ?? [];
    }

    /** @param array<string, mixed>|null $payload */
    public function createPortal(?array $payload = null): array
    {
        return $this->client->post('/v1/billing/portal', $payload) ?? [];
    }
}
