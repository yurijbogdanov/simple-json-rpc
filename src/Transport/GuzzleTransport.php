<?php
declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Dto\ResponseDtoFactory;
use SimpleJsonRpc\Exception\TransportException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GuzzleTransport.
 */
final class GuzzleTransport implements TransportInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $dsn
     * @param array $options
     */
    public function __construct(string $dsn, array $options = [])
    {
        $dsnObj = Dsn::createFromString($dsn);

        $uri = $this->createUri($dsnObj);
        $username = $dsnObj->getUser();
        $password = $dsnObj->getPassword();

        $this->client = new Client(\array_merge($options, [
            'base_uri' => $uri,
            'auth' => [$username, $password],
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestDto $requestDto): ResponseDto
    {
        try {
            $response = $this->client->request('POST', '', ['json' => $requestDto]);

            return (new ResponseDtoFactory())->createFromArray(\json_decode((string)$response->getBody(), true));
        } catch (\Throwable $e) {
            if ($e instanceof RequestException && null !== $response = $e->getResponse()) {
                return (new ResponseDtoFactory())->createFromArray(\json_decode((string)$response->getBody(), true));
            }

            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Dsn $dsn
     *
     * @return string
     */
    private function createUri(Dsn $dsn): string
    {
        return \sprintf(
            '%s://%s%s/%s',
            $dsn->getScheme(),
            $dsn->getHost(),
            $dsn->getPort() ? (':' . $dsn->getPort()) : '',
            \ltrim($dsn->getPath(), '/')
        );
    }
}
