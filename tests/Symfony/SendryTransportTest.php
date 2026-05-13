<?php

declare(strict_types=1);

namespace Sendry\Tests\Symfony;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Sendry\Sendry;
use Sendry\Symfony\SendryTransport;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * @internal
 */
final class SendryTransportTest extends TestCase
{
    public function testConvertsSymfonyEmailToSendryPayload(): void
    {
        /** @var RequestInterface[] $captured */
        $captured = [];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => 'email_test_123'])),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($captured));

        $sendry = new Sendry('sn_test_unit', ['handler' => $stack]);
        $transport = new SendryTransport($sendry);

        $email = (new Email())
            ->from(new Address('alice@example.com', 'Alice'))
            ->to(new Address('bob@example.com', 'Bob'))
            ->subject('Hello')
            ->text('hi there')
            ->html('<p>hi there</p>');

        $envelope = new Envelope(new Address('alice@example.com'), [new Address('bob@example.com')]);

        $transport->send($email, $envelope);

        $this->assertCount(1, $captured);
        $request = $captured[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/v1/emails', $request->getUri()->getPath());

        $body = json_decode((string) $request->getBody(), true);
        $this->assertIsArray($body);
        $this->assertSame('Hello', $body['subject']);
        $this->assertStringContainsString('alice@example.com', $body['from']);
        $this->assertStringContainsString('bob@example.com', $body['to'][0]);
        $this->assertSame('hi there', $body['text']);
        $this->assertSame('<p>hi there</p>', $body['html']);
    }
}
