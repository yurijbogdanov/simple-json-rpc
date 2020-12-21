<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class ProcedureNotFoundException.
 */
final class ProcedureNotFoundException extends ServerException
{
    /**
     * {@inheritdoc}
     */
    public function getHttpCode(): int
    {
        return 404;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorCode(): int
    {
        return -32601;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorMessage(): string
    {
        return 'Procedure not found';
    }
}
