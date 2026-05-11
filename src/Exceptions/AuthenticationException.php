<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/** Thrown on HTTP 401 — API key missing or invalid. */
final class AuthenticationException extends ApiException
{
}
