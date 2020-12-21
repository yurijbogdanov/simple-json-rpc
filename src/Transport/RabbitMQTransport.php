<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Dto\ResponseDtoFactory;
use SimpleJsonRpc\Exception\TransportException;

/**
 * Class RabbitMQTransport.
 */
final class RabbitMQTransport implements TransportInterface
{
    /**
     * @var string
     */
    private $queue;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @param string $dsn
     * @param array  $options
     */
    public function __construct(string $dsn, array $options = [])
    {
        $dsnObj = Dsn::createFromString($dsn);

        $pathParts = $dsnObj->getPath() ? explode('/', trim($dsnObj->getPath(), '/')) : [];
        $vhost = isset($pathParts[0]) ? urldecode($pathParts[0]) : '/';
        $this->queue = isset($pathParts[1]) ? urldecode($pathParts[1]) : 'rpc';

        $this->connection = new AMQPStreamConnection(
            $dsnObj->getHost(),
            $dsnObj->getPort(),
            $dsnObj->getUser(),
            $dsnObj->getPassword(),
            $vhost
        );
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestDto $requestDto): ResponseDto
    {
        try {
            /** @var null|string $response */
            $response = null;
            $correlationId = $this->createCorrelationId();
            $callbackQueue = $this->createCallbackQueue($correlationId);
            $callback = $this->createCallback($correlationId, $response);

            $channel = $this->connection->channel();
            $channel->queue_declare($callbackQueue, false, false, true, false);
            $channel->basic_consume($callbackQueue, '', false, true, false, false, $callback);
            $channel->basic_publish($this->createMessage($requestDto, $correlationId, $callbackQueue), '', $this->queue);

            while (null === $response) {
                $channel->wait();
            }

            return (new ResponseDtoFactory())->createFromJson($response);
        } catch (\Throwable $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        } finally {
            if (isset($channel)) {
                $channel->close();
            }
        }
    }

    /**
     * @param string      $correlationId
     * @param null|string $response
     *
     * @return \Closure
     */
    private function createCallback(string $correlationId, ?string &$response): \Closure
    {
        return static function (AMQPMessage $message) use ($correlationId, &$response): void {
            if ($correlationId === $message->get('correlation_id')) {
                $response = (string) $message->getBody();
            }
        };
    }

    /**
     * @return string
     */
    private function createCorrelationId(): string
    {
        return sha1(uniqid('', true));
    }

    /**
     * @param RequestDto $requestDto
     * @param string     $correlationId
     * @param string     $callbackQueue
     *
     * @return AMQPMessage
     */
    private function createMessage(RequestDto $requestDto, string $correlationId, string $callbackQueue): AMQPMessage
    {
        return new AMQPMessage(
            json_encode($requestDto),
            [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'correlation_id' => $correlationId,
                'reply_to' => $callbackQueue,
            ]
        );
    }

    /**
     * @param string $correlationId
     *
     * @return string
     */
    private function createCallbackQueue(string $correlationId): string
    {
        return 'amq__rpc__'.$correlationId;
    }
}
