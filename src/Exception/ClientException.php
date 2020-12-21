<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class ClientException.
 */
class ClientException extends \RuntimeException implements ClientExceptionInterface
{
    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var string
     */
    private $errorInfo;

    /**
     * @param int             $errorCode
     * @param string          $errorMessage
     * @param string          $errorInfo
     * @param null|\Throwable $previous
     */
    public function __construct(int $errorCode, string $errorMessage, string $errorInfo, \Throwable $previous = null)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->errorInfo = $errorInfo;

        parent::__construct($errorMessage, $errorCode, $previous);
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->errorInfo;
    }
}
