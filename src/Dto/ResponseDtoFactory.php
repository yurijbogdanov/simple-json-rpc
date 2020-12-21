<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Dto;

use SimpleJsonRpc\Exception\InvalidJsonRpcErrorException;
use SimpleJsonRpc\Exception\ParseErrorException;

/**
 * Class ResponseDtoFactory.
 */
final class ResponseDtoFactory
{
    /**
     * @param array $data
     *
     * @throws InvalidJsonRpcErrorException
     *
     * @return ResponseDto
     */
    public function createFromArray(array $data): ResponseDto
    {
        $result = $data['result'] ?? null;
        $id = $data['id'] ?? null;
        $error = $data['error'] ?? null;

        return new ResponseDto($result, $id, $error);
    }

    /**
     * @param string $json
     *
     * @throws ParseErrorException
     *
     * @return ResponseDto
     */
    public function createFromJson(string $json): ResponseDto
    {
        $data = json_decode($json, true);

        if (!\is_array($data)) {
            throw new ParseErrorException('Not array');
        }

        return $this->createFromArray($data);
    }

    /**
     * @param \Throwable  $e
     * @param null|string $id
     *
     * @return ResponseDto
     */
    public function createFromException(\Throwable $e, ?string $id = null): ResponseDto
    {
        $errorCode = $e->getCode();
        if (0 === $errorCode) {
            $errorCode = -32603;
        }

        return new ResponseDto(
            null,
            $id,
            ['code' => $errorCode, 'message' => 'Internal Error', 'info' => $e->getMessage()]
        );
    }
}
