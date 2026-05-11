<?php

declare(strict_types=1);

namespace Sendry\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sendry\Exceptions\AuthenticationException;
use Sendry\Exceptions\NotFoundException;
use Sendry\Exceptions\RateLimitException;
use Sendry\Exceptions\ValidationException;
use Sendry\Resources\Domains;
use Sendry\Resources\Emails;
use Sendry\Sendry;

final class SendryTest extends TestCase
{
    public function testResourceAccessors(): void
    {
        $sendry = new Sendry('sn_live_test');

        $this->assertInstanceOf(Emails::class, $sendry->emails());
        $this->assertInstanceOf(Domains::class, $sendry->domains());
        // Property access mirrors method access.
        $this->assertSame($sendry->emails(), $sendry->emails);
    }

    public function testEmailsSend(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'em_abc', 'status' => 'queued'])),
        ]);
        $client = $this->makeClient($mock);

        $resp = $client->emails()->send([
            'from'    => 'a@b.com',
            'to'      => 'c@d.com',
            'subject' => 'Hi',
            'html'    => '<p>Hi</p>',
        ]);

        $this->assertSame('em_abc', $resp['id']);
    }

    public function testAuthenticationError(): void
    {
        $mock = new MockHandler([
            new Response(401, [], json_encode([
                'error' => ['code' => 'invalid_api_key', 'message' => 'Bad key'],
            ])),
        ]);
        $client = $this->makeClient($mock);

        $this->expectException(AuthenticationException::class);
        $client->emails()->send(['from' => 'a@b.com', 'to' => 'c@d.com', 'subject' => 'x', 'html' => 'y']);
    }

    public function testNotFoundError(): void
    {
        $mock = new MockHandler([new Response(404, [], json_encode(['error' => ['code' => 'not_found', 'message' => 'gone']]))]);
        $client = $this->makeClient($mock);

        $this->expectException(NotFoundException::class);
        $client->emails()->get('em_missing');
    }

    public function testValidationErrorIncludesDetails(): void
    {
        $mock = new MockHandler([new Response(422, [], json_encode([
            'error' => ['code' => 'validation_error', 'message' => 'bad', 'details' => ['from' => 'required']],
        ]))]);
        $client = $this->makeClient($mock);

        try {
            $client->emails()->send([]);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertSame(['from' => 'required'], $e->details);
        }
    }

    public function testRateLimitRetryAfter(): void
    {
        $mock = new MockHandler([
            new Response(429, ['Retry-After' => '7'], json_encode([
                'error' => ['code' => 'rate_limit', 'message' => 'slow down'],
            ])),
        ]);
        $client = $this->makeClient($mock);

        try {
            $client->emails()->send(['from' => 'a@b.com', 'to' => 'c@d.com', 'subject' => 'x', 'html' => 'y']);
            $this->fail('Expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertSame(7, $e->retryAfter);
        }
    }

    public function testWebhookSignatureValid(): void
    {
        $body = '{"event":"email.delivered"}';
        $secret = 'whsec_abc';
        $sig = hash_hmac('sha256', $body, $secret);

        $this->assertTrue(Sendry::verifyWebhookSignature($body, $sig, $secret));
        $this->assertTrue(Sendry::verifyWebhookSignature($body, "sha256={$sig}", $secret));
        $this->assertFalse(Sendry::verifyWebhookSignature($body, 'deadbeef', $secret));
    }

    private function makeClient(MockHandler $mock): Sendry
    {
        return new Sendry('sn_live_test', [
            'handler' => HandlerStack::create($mock),
            'retries' => 0,
        ]);
    }
}
