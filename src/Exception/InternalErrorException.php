<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class InternalErrorException.
 */
final class InternalErrorException extends ServerException
{
    /**
     * {@inheritdoc}
     */
    public function getHttpCode(): int
    {
        return 500;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorCode(): int
    {
        return -32603;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Internal error';
    }
}
