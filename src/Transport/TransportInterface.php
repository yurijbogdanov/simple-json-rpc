<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Exception\TransportException;

/**
 * Interface TransportInterface.
 */
interface TransportInterface
{
    /**
     * @param RequestDto $requestDto
     *
     * @throws TransportException
     *
     * @return ResponseDto
     */
    public function send(RequestDto $requestDto): ResponseDto;
}
