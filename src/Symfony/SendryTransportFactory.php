<?php

declare(strict_types=1);

namespace Sendry\Symfony;

use Sendry\Sendry;
use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * Builds a SendryTransport from a `sendry://<api_key>@<host>` DSN.
 *
 * Example DSNs:
 *   sendry://sn_live_xxx@default
 *   sendry://sn_live_xxx@api.sendry.online
 */
final class SendryTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        if ('sendry' !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, 'sendry', $this->getSupportedSchemes());
        }

        $apiKey = $this->getUser($dsn);
        $host = $dsn->getHost();
        $baseUrl = $host === 'default' ? 'https://api.sendry.online' : sprintf('https://%s', $host);

        $sendry = new Sendry($apiKey, ['base_url' => $baseUrl]);

        return new SendryTransport($sendry, $this->dispatcher, $this->logger);
    }

    /** @return string[] */
    protected function getSupportedSchemes(): array
    {
        return ['sendry'];
    }
}
