<?php
declare(strict_types=1);

namespace SimpleJsonRpc;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Exception\ClientException;
use SimpleJsonRpc\Exception\ClientExceptionInterface;
use SimpleJsonRpc\Exception\TransportException;
use SimpleJsonRpc\Transport\TransportInterface;

/**
 * Class Client.
 */
class Client implements ClientInterface
{
    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @param TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function callMethod(string $method, array $params = [])
    {
        try {
            $id = \sha1(\uniqid('', true));

            $responseDto = $this->transport->send(new RequestDto($method, $params, $id));
        } catch (TransportException $e) {
            throw new ClientException(-112233, 'Transport exception', $e->getMessage(), $e);
        } catch (\Throwable $e) {
            throw new ClientException(-332211, 'Exception', $e->getMessage(), $e);
        }

        if ($responseDto->hasError()) {
            throw new ClientException($responseDto->getErrorCode(), $responseDto->getErrorMessage(), $responseDto->getErrorInfo());
        }

        return $responseDto->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodsList(): array
    {
        return $this->callMethod('getMethodsList');
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @throws ClientExceptionInterface
     *
     * @return mixed
     */
    public function __call(string $method, array $params)
    {
        return $this->callMethod($method, $params);
    }
}
