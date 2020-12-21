<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Dto;

use SimpleJsonRpc\Exception\InvalidJsonRpcErrorException;
use SimpleJsonRpc\Exception\ParseErrorException;

/**
 * Class RequestDtoFactory.
 */
final class RequestDtoFactory
{
    /**
     * @param array $data
     *
     * @return RequestDto
     */
    public function createFromArray(array $data): RequestDto
    {
        if (!isset($data['method'])) {
            throw new InvalidJsonRpcErrorException('No method');
        }
        if (!isset($data['params'])) {
            throw new InvalidJsonRpcErrorException('No params');
        }

        $id = $data['id'] ?? null;
        $method = $data['method'];
        $params = $data['params'];

        return new RequestDto($method, $params, $id);
    }

    /**
     * @param string $json
     *
     * @throws ParseErrorException
     *
     * @return RequestDto
     */
    public function createFromJson(string $json): RequestDto
    {
        $data = json_decode($json, true);

        if (!\is_array($data)) {
            throw new ParseErrorException('Not array');
        }

        return $this->createFromArray($data);
    }
}
