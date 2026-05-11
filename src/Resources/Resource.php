<?php

declare(strict_types=1);

namespace Sendry\Resources;

use Sendry\HttpClient;

/**
 * Shared base class for every API resource. Holds the HTTP client so concrete
 * resources only declare endpoint methods.
 *
 * @internal
 */
abstract class Resource
{
    public function __construct(
        protected readonly HttpClient $client,
    ) {
    }
}
