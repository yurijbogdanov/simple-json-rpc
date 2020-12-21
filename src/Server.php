<?php
declare(strict_types=1);

namespace SimpleJsonRpc;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Dto\RequestDtoFactory;
use SimpleJsonRpc\Exception\InternalErrorException;
use SimpleJsonRpc\Exception\ProcedureNotFoundException;

/**
 * Class Server.
 */
class Server implements ServerInterface
{
    /**
     * @var array<string, callable>
     */
    private $methodMap;

    /**
     * @param array<string, callable> $methods
     */
    public function __construct(array $methods)
    {
        foreach ($methods as $method => $handler) {
            $this->registerMethod($method, $handler);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerMethod(string $method, callable $handler): void
    {
        $this->methodMap[$method] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodsList(): array
    {
        return \array_keys($this->methodMap);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveProcedure(string $method): ?callable
    {
        return $this->methodMap[$method] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function executeProcedure(callable $procedure, array $params)
    {
        return $procedure(...$params);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(RequestDto $requestDto): ResponseDto
    {
        $method = $requestDto->getMethod();

        if (null === $procedure = $this->resolveProcedure($method)) {
            throw new ProcedureNotFoundException(\sprintf('Method "%s" not found', $method));
        }

        try {
            $params = $requestDto->getParams();
            $result = $this->executeProcedure($procedure, $params);

            return new ResponseDto($result, $requestDto->getId());
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handleFromJson(string $json, ?string &$id = null): ResponseDto
    {
        $requestDtoFactory = new RequestDtoFactory();
        $requestDto = $requestDtoFactory->createFromJson($json);

        $id = $requestDto->getId();

        return $this->handle($requestDto);
    }
}
