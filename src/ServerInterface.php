<?php
declare(strict_types=1);

namespace SimpleJsonRpc;

use SimpleJsonRpc\Dto\RequestDto;
use SimpleJsonRpc\Dto\ResponseDto;
use SimpleJsonRpc\Exception\InternalErrorException;
use SimpleJsonRpc\Exception\ProcedureNotFoundException;

/**
 * Interface ServerInterface.
 */
interface ServerInterface
{
    /**
     * @param string $method
     * @param callable $handler
     *
     * @return void
     */
    public function registerMethod(string $method, callable $handler): void;

    /**
     * @return array|string[]
     */
    public function getMethodsList(): array;

    /**
     * @param string $method
     *
     * @return callable|null
     */
    public function resolveProcedure(string $method): ?callable;

    /**
     * @param callable $procedure
     * @param array $params
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public function executeProcedure(callable $procedure, array $params);

    /**
     * @param RequestDto $requestDto
     *
     * @throws ProcedureNotFoundException
     * @throws InternalErrorException
     *
     * @return ResponseDto
     */
    public function handle(RequestDto $requestDto): ResponseDto;

    /**
     * @param string $json
     * @param string|null $id
     *
     * @throws ProcedureNotFoundException
     * @throws InternalErrorException
     *
     * @return ResponseDto
     */
    public function handleFromJson(string $json, ?string &$id = null): ResponseDto;
}
