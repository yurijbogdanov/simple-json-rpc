<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Exception;

/**
 * Class ServerException.
 */
abstract class ServerException extends \RuntimeException implements ServerExceptionInterface
{
    /**
     * @var string
     */
    private $info;

    /**
     * @param string $info
     * @param \Throwable|null $previous
     */
    public function __construct(string $info = '', ?\Throwable $previous = null)
    {
        $this->info = $info;

        parent::__construct($this->getErrorMessage(), $this->getErrorCode(), $previous);
    }

    /**
     * @return int
     */
    abstract public function getHttpCode(): int;

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function getError(): array
    {
        return [$this->getErrorCode(), $this->getErrorMessage(), $this->getErrorInfo()];
    }

    /**
     * @return int
     */
    abstract protected function getErrorCode(): int;

    /**
     * @return string
     */
    abstract protected function getErrorMessage(): string;

    /**
     * @return string
     */
    protected function getErrorInfo(): string
    {
        return $this->getInfo();
    }
}
