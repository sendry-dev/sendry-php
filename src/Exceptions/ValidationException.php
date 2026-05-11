<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/** Thrown on HTTP 422 — request body or parameters failed validation. */
final class ValidationException extends ApiException
{
}
