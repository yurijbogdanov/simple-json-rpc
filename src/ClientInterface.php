<?php

declare(strict_types=1);

namespace SimpleJsonRpc;

use SimpleJsonRpc\Exception\ClientExceptionInterface;

/**
 * Interface ClientInterface.
 */
interface ClientInterface
{
    /**
     * @param string $method
     * @param array  $params
     *
     * @throws ClientExceptionInterface
     *
     * @return mixed
     */
    public function callMethod(string $method, array $params = []);

    /**
     * @throws ClientExceptionInterface
     *
     * @return array
     */
    public function getMethodsList(): array;
}
