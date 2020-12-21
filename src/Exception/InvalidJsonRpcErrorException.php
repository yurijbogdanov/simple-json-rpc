<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class InvalidJsonRpcErrorException.
 */
final class InvalidJsonRpcErrorException extends ServerException
{
    /**
     * {@inheritdoc}
     */
    public function getHttpCode(): int
    {
        return 400;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorCode(): int
    {
        return -32600;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Invalid JSON-RPC';
    }
}
