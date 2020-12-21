<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

/**
 * Class NullTransportFactory.
 */
final class NullTransportFactory implements TransportFactoryInterface
{
    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return TransportInterface
     */
    public function createTransport(string $dsn, array $options): TransportInterface
    {
        return new NullTransport();
    }

    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'null://');
    }
}
