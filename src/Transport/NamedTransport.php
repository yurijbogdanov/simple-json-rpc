<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;

/**
 * Class NamedTransport.
 */
final class NamedTransport implements NamedTransportInterface
{
    /**
     * @var string
     */
    private $transportId;

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var bool
     */
    private $isDefault;

    /**
     * @param string $transportId
     * @param TransportInterface $transport
     * @param bool $isDefault
     */
    public function __construct(string $transportId, TransportInterface $transport, bool $isDefault = false)
    {
        $this->transportId = $transportId;
        $this->transport = $transport;
        $this->isDefault = $isDefault;
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestDto $requestDto): ResponseDto
    {
        return $this->transport->send($requestDto);
    }

    /**
     * @return string
     */
    public function getTransportId(): string
    {
        return $this->transportId;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }
}
