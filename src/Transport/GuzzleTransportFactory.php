<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

/**
 * Class GuzzleTransportFactory.
 */
final class GuzzleTransportFactory implements TransportFactoryInterface
{
    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return TransportInterface
     */
    public function createTransport(string $dsn, array $options): TransportInterface
    {
        return new GuzzleTransport($dsn, $options);
    }

    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'https://') || str_starts_with($dsn, 'http://');
    }
}
