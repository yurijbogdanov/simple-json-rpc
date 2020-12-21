<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

/**
 * Interface NamedTransportInterface.
 */
interface NamedTransportInterface extends TransportInterface
{
    /**
     * @return string
     */
    public function getTransportId(): string;

    /**
     * @return bool
     */
    public function isDefault(): bool;
}
