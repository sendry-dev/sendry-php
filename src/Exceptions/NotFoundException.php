<?php

declare(strict_types=1);

namespace Sendry\Exceptions;

/** Thrown on HTTP 404 — requested resource does not exist. */
final class NotFoundException extends ApiException
{
}
