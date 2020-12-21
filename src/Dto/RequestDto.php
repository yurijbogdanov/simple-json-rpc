<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Dto;

/**
 * Class RequestDto.
 *
 * {"jsonrpc": "2.0", "method": "add", "params": [23, 42], "id": 2}
 * {"jsonrpc": "2.0", "method": "add", "params": [23, 42], "id": null}
 */
final class RequestDto implements \JsonSerializable
{
    /**
     * @var string
     */
    private $jsonrpc = '2.0';

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * @var null|string
     */
    private $id;

    /**
     * @param string      $method
     * @param array       $params
     * @param null|string $id
     */
    public function __construct(string $method, array $params = [], ?string $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
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
    public function toArray(): array
    {
        return [
            'jsonrpc' => $this->getJsonrpc(),
            'method' => $this->getMethod(),
            'params' => $this->getParams(),
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
}
