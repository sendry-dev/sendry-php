<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/** Generic API error returned by Sendry. Subclasses cover 401, 404, 422, 429. */
class ApiException extends SendryException
{
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly string $errorCode,
        public readonly mixed $details = null,
    ) {
        parent::__construct(
            sprintf('sendry: %s (status=%d, code=%s)', $message, $statusCode, $errorCode),
        );
    }
}
