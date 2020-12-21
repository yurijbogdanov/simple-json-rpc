<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class ParseErrorException.
 */
final class ParseErrorException extends ServerException
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
        return -32700;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Parse error';
    }
}
