<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/** Thrown on HTTP 429 — rate limit hit. `retryAfter` holds the seconds to wait. */
final class RateLimitException extends ApiException
{
    public function __construct(
        string $message,
        int $statusCode,
        string $errorCode,
        public readonly int $retryAfter = 0,
    ) {
        parent::__construct($message, $statusCode, $errorCode);
    }
}
