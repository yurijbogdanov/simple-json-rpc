<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Exception\TransportException;

/**
 * Class MultiTransport.
 */
final class MultiTransport implements TransportInterface
{
    /**
     * @var array<string, TransportInterface>
     */
    private $transportMap = [];

    /**
     * @var array<string, string>
     */
    private $methodMap = [];

    /**
     * @var TransportInterface|null
     */
    private $defaultTransport;

    /**
     * @param array|NamedTransportInterface[] $transports
     * @param array|array<string, string> $methods
     */
    public function __construct(array $transports, array $methods = [])
    {
        foreach ($transports as $transport) {
            $this->registerTransport($transport);
        }

        foreach ($methods as $method => $transportId) {
            $this->registerMethod($method, $transportId);
        }

        if (null === $this->defaultTransport) {
            throw new \InvalidArgumentException('no default transport');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestDto $requestDto): ResponseDto
    {
        return $this->resolveTransport($requestDto)->send($requestDto);
    }

    /**
     * @param NamedTransportInterface $transport
     *
     * @return void
     */
    private function registerTransport(NamedTransportInterface $transport): void
    {
        $this->transportMap[$transport->getTransportId()] = $transport;

        if ($transport->isDefault()) {
            $this->defaultTransport = $transport;
        }
    }

    /**
     * @param string $method
     * @param string $transportId
     *
     * @return void
     */
    private function registerMethod(string $method, string $transportId): void
    {
        if (!isset($this->transportMap[$transportId])) {
            throw new \InvalidArgumentException('no transport id');
        }

        $this->methodMap[$method] = $transportId;
    }

    /**
     * @param RequestDto $requestDto
     *
     * @return TransportInterface
     */
    private function resolveTransport(RequestDto $requestDto): TransportInterface
    {
        $method = $requestDto->getMethod();

        $transportId = $this->methodMap[$method] ?? null;

        if (null === $transportId) {
            return $this->defaultTransport;
        }

        $transport = $this->transportMap[$transportId] ?? null;

        if (null === $transport) {
            throw new TransportException(\sprintf('transport not found for the method "%s"', $method));
        }

        return $transport;
    }
}
