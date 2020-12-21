<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Dto;

/**
 * Class ResponseDto.
 *
 * {"jsonrpc": "2.0", "result": 19, "id": 10, "error": null}
 * {"jsonrpc": "2.0", "result": 19, "id": null, "error": null}
 * {"jsonrpc": "2.0", "result": null, "id": 10, "error": {"code": -32601, "message": "Procedure not found", "info": "Procedure 'add' not found"}}
 * {"jsonrpc": "2.0", "result": null, "id": null, "error": {"code": -32601, "message": "Procedure not found", "info": "Procedure 'add' not found"}}
 */
final class ResponseDto implements \JsonSerializable
{
    public const CODE_NO_ERRORS = 0;

    /**
     * @var string
     */
    private $jsonrpc = '2.0';

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var array
     */
    private $error;

    /**
     * @var null|string
     */
    private $id;

    /**
     * @param mixed       $result Result
     * @param null|string $id
     * @param null|array  $error  Error
     */
    public function __construct($result, ?string $id = null, array $error = null)
    {
        $this->result = $result;
        $this->id = $id;
        $this->error = $error ?? ['code' => self::CODE_NO_ERRORS, 'message' => '', 'info' => ''];

        // TODO Validate
    }

    /**
     * @return string
     */
    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getError(): array
    {
        return $this->error;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->error['code'];
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->error['message'];
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->error['info'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'jsonrpc' => $this->getJsonrpc(),
            'result' => $this->getResult(),
            'error' => $this->getError(),
            'id' => $this->getId(),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return self::CODE_NO_ERRORS !== $this->getErrorCode();
    }
}
