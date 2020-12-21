<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Interface ClientExceptionInterface.
 */
interface ClientExceptionInterface extends SimpleJsonRpcExceptionInterface
{
    /**
     * @return int
     */
    public function getErrorCode(): int;

    /**
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * @return string
     */
    public function getErrorInfo(): string;
}
