<?php

declare(strict_types=1);

namespace Sendry\Symfony;

use Sendry\Sendry;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Header\TagHeader;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Symfony Mailer transport backed by the Sendry HTTP API.
 *
 * Registered automatically in Laravel by SendryServiceProvider when the
 * `mail.mailers.<name>.transport` config equals `"sendry"`. In plain Symfony
 * apps, wire SendryTransportFactory into your transport chain and use the
 * `sendry://default` DSN.
 */
final class SendryTransport extends AbstractTransport
{
    public function __construct(
        private readonly Sendry $sendry,
        ?EventDispatcherInterface $dispatcher = null,
        ?LoggerInterface $logger = null,
    ) {
        parent::__construct($dispatcher, $logger);
    }

    public function __toString(): string
    {
        return 'sendry://api';
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $payload = $this->buildPayload($email, $message->getEnvelope());
        $response = $this->sendry->emails()->send($payload);

        if (isset($response['id']) && is_string($response['id'])) {
            $message->setMessageId($response['id']);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(Email $email, Envelope $envelope): array
    {
        $from = $email->getFrom();
        if ($from === []) {
            // Symfony Mailer normally enforces a From, but be defensive.
            $sender = $envelope->getSender();
            $from = [$sender];
        }

        $payload = [
            'from'    => $this->formatAddress($from[0]),
            'to'      => array_map(fn ($a) => $this->formatAddress($a), $email->getTo() ?: $envelope->getRecipients()),
            'subject' => (string) $email->getSubject(),
        ];

        if ($cc = $email->getCc()) {
            $payload['cc'] = array_map(fn ($a) => $this->formatAddress($a), $cc);
        }
        if ($bcc = $email->getBcc()) {
            $payload['bcc'] = array_map(fn ($a) => $this->formatAddress($a), $bcc);
        }
        if ($replyTo = $email->getReplyTo()) {
            $payload['reply_to'] = array_map(fn ($a) => $this->formatAddress($a), $replyTo);
        }
        if (($html = $email->getHtmlBody()) !== null && $html !== '') {
            $payload['html'] = is_string($html) ? $html : stream_get_contents($html);
        }
        if (($text = $email->getTextBody()) !== null && $text !== '') {
            $payload['text'] = is_string($text) ? $text : stream_get_contents($text);
        }

        $headers = [];
        foreach ($email->getHeaders()->all() as $header) {
            if ($header instanceof TagHeader) {
                continue;
            }
            $name = $header->getName();
            // Strip standard headers Sendry derives from structured fields.
            if (in_array(strtolower($name), [
                'from', 'to', 'cc', 'bcc', 'reply-to', 'subject',
                'content-type', 'content-transfer-encoding', 'date',
                'message-id', 'mime-version',
            ], true)) {
                continue;
            }
            $headers[$name] = $header->getBodyAsString();
        }
        if ($headers !== []) {
            $payload['headers'] = $headers;
        }

        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            $body = $attachment->getBody();
            $headers = $attachment->getPreparedHeaders();
            $contentDisposition = $headers->get('content-disposition');
            $filename = $contentDisposition?->getParameter('filename') ?? 'attachment';
            $contentType = $attachment->getMediaType() . '/' . $attachment->getMediaSubtype();
            $contentId = $headers->get('content-id')?->getBodyAsString();
            $contentId = $contentId !== null ? trim($contentId, '<>') : null;

            $attachments[] = array_filter([
                'filename'     => $filename,
                'content'      => base64_encode($body),
                'content_type' => $contentType,
                'cid'          => $contentId,
            ], static fn ($v) => $v !== null);
        }
        if ($attachments !== []) {
            $payload['attachments'] = $attachments;
        }

        return $payload;
    }

    private function formatAddress(mixed $addr): string
    {
        if ($addr === null) {
            return '';
        }
        // Symfony\Component\Mime\Address has ->toString() returning "Name <email>".
        if (is_object($addr) && method_exists($addr, 'toString')) {
            return $addr->toString();
        }
        return (string) $addr;
    }
}
