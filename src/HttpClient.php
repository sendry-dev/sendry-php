<?php

declare(strict_types=1);

namespace Sendry;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Sendry\Exceptions\ApiException;
use Sendry\Exceptions\AuthenticationException;
use Sendry\Exceptions\NetworkException;
use Sendry\Exceptions\NotFoundException;
use Sendry\Exceptions\RateLimitException;
use Sendry\Exceptions\ValidationException;

/**
 * Low-level HTTP client. Resource classes are the public surface;
 * application code never touches HttpClient directly.
 *
 * @internal
 */
final class HttpClient
{
    private const DEFAULT_BASE_URL = 'https://api.sendry.online';
    private const DEFAULT_TIMEOUT  = 30.0;
    private const DEFAULT_RETRIES  = 2;

    private readonly Client $http;
    private readonly string $apiKey;
    private readonly string $baseUrl;
    private readonly int $retries;
    private readonly float $timeout;

    /**
     * @param array{
     *   base_url?: string,
     *   timeout?: float|int,
     *   retries?: int,
     *   headers?: array<string, string>,
     *   handler?: callable,
     * } $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        if ($apiKey === '') {
            throw new \InvalidArgumentException(
                'Missing API key. Pass it to the Sendry constructor.',
            );
        }

        $this->apiKey  = $apiKey;
        $this->baseUrl = rtrim($options['base_url'] ?? self::DEFAULT_BASE_URL, '/');
        $this->timeout = (float) ($options['timeout'] ?? self::DEFAULT_TIMEOUT);
        $this->retries = (int) ($options['retries'] ?? self::DEFAULT_RETRIES);

        $config = [
            'base_uri' => $this->baseUrl,
            'timeout'  => $this->timeout,
            'http_errors' => false,
            'headers'  => array_merge([
                'Authorization' => "Bearer {$this->apiKey}",
                'User-Agent'    => 'sendry-php/' . Sendry::VERSION,
                'Accept'        => 'application/json',
            ], $options['headers'] ?? []),
        ];
        if (isset($options['handler'])) {
            $config['handler'] = $options['handler'];
        }

        $this->http = new Client($config);
    }

    /** @return array<mixed>|null */
    public function get(string $path, array $query = []): ?array
    {
        return $this->request('GET', $path, query: $query);
    }

    /** @return array<mixed>|null */
    public function post(string $path, ?array $body = null): ?array
    {
        return $this->request('POST', $path, body: $body);
    }

    /** @return array<mixed>|null */
    public function put(string $path, ?array $body = null): ?array
    {
        return $this->request('PUT', $path, body: $body);
    }

    /** @return array<mixed>|null */
    public function patch(string $path, ?array $body = null): ?array
    {
        return $this->request('PATCH', $path, body: $body);
    }

    /** @return array<mixed>|null */
    public function delete(string $path): ?array
    {
        return $this->request('DELETE', $path);
    }

    /** @return array<mixed>|null */
    private function request(
        string $method,
        string $path,
        ?array $body = null,
        array $query = [],
    ): ?array {
        $options = [];
        if ($body !== null) {
            $options['json'] = $body;
        }
        if ($query !== []) {
            $options['query'] = array_filter(
                $query,
                static fn($v) => $v !== null,
            );
        }

        $lastError = null;
        for ($attempt = 0; $attempt <= $this->retries; $attempt++) {
            if ($attempt > 0) {
                usleep((int) (min(0.2 * (2 ** ($attempt - 1)), 5.0) * 1_000_000));
            }

            try {
                $response = $this->http->request($method, $path, $options);
            } catch (ConnectException $e) {
                $lastError = new NetworkException($e->getMessage(), 0, $e);
                continue;
            } catch (RequestException $e) {
                $lastError = new NetworkException($e->getMessage(), 0, $e);
                continue;
            }

            $status = $response->getStatusCode();

            if ($status >= 200 && $status < 300) {
                if ($status === 204) {
                    return null;
                }
                /** @var array<mixed> $decoded */
                $decoded = json_decode((string) $response->getBody(), true) ?? [];
                return $decoded;
            }

            $error = $this->buildError($response);

            // Non-retryable client errors
            if ($status < 500) {
                throw $error;
            }

            $lastError = $error;
        }

        throw $lastError ?? new NetworkException('Unknown network failure');
    }

    private function buildError(ResponseInterface $response): ApiException
    {
        $status = $response->getStatusCode();
        $body   = (string) $response->getBody();
        $parsed = json_decode($body, true);
        $error  = is_array($parsed) ? ($parsed['error'] ?? []) : [];

        $code    = is_array($error) ? ($error['code']    ?? 'unknown_error') : 'unknown_error';
        $message = is_array($error) ? ($error['message'] ?? $response->getReasonPhrase()) : $response->getReasonPhrase();
        $details = is_array($error) ? ($error['details'] ?? null) : null;

        return match (true) {
            $status === 401 => new AuthenticationException($message, $status, $code),
            $status === 404 => new NotFoundException($message, $status, $code),
            $status === 422 => new ValidationException($message, $status, $code, $details),
            $status === 429 => new RateLimitException(
                $message,
                $status,
                $code,
                (int) ($response->getHeaderLine('Retry-After') ?: 0),
            ),
            default => new ApiException($message, $status, $code, $details),
        };
    }
}
