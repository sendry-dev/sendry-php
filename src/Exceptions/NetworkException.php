<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/**
 * Thrown when a network-level failure occurred (DNS, connection refused,
 * timeout) — no HTTP response was received from the API.
 */
final class NetworkException extends SendryException
{
}
