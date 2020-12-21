<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

/**
 * Interface TransportFactoryInterface.
 */
interface TransportFactoryInterface
{
    /**
     * @param string $dsn
     * @param array $options
     *
     * @return TransportInterface
     */
    public function createTransport(string $dsn, array $options): TransportInterface;

    /**
     * @param string $dsn
     * @param array $options
     *
     * @return bool
     */
    public function supports(string $dsn, array $options): bool;
}
