<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;

/**
 * Class NullTransport.
 */
final class NullTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(RequestDto $requestDto): ResponseDto
    {
        return new ResponseDto(null);
    }
}
