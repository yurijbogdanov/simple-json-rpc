<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

/**
 * Class RabbitMQTransportFactory.
 */
final class RabbitMQTransportFactory implements TransportFactoryInterface
{
    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return TransportInterface
     */
    public function createTransport(string $dsn, array $options): TransportInterface
    {
        return new RabbitMQTransport($dsn, $options);
    }

    /**
     * @param string $dsn
     * @param array  $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'amqp://');
    }
}
