<?php

declare(strict_types=1);

namespace SimpleJsonRpc\Transport;

use SimpleJsonRpc\Exception\DsnException;

/**
 * Class Dsn.
 */
final class Dsn
{
    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var null|int
     */
    private $port;

    /**
     * @var null|string
     */
    private $user;

    /**
     * @var null|string
     */
    private $password;

    /**
     * @var null|string
     */
    private $path;

    /**
     * @var array<string, mixed>
     */
    private $query;

    /**
     * @param string      $scheme
     * @param string      $host
     * @param null|int    $port
     * @param null|string $user
     * @param null|string $password
     * @param null|string $path
     * @param array       $query
     */
    public function __construct(string $scheme, string $host, ?int $port = null, ?string $user = null, ?string $password = null, ?string $path = null, array $query = [])
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
    }

    /**
     * @param string $dsn
     *
     * @return self
     */
    public static function createFromString(string $dsn): self
    {
        if (false === $parsedDsn = parse_url($dsn)) {
            throw new DsnException(sprintf('The "%s" transport DSN is invalid.', $dsn));
        }

        if (!isset($parsedDsn['scheme'])) {
            throw new DsnException(sprintf('The "%s" transport DSN must contain a scheme.', $dsn));
        }

        if (!isset($parsedDsn['host'])) {
            throw new DsnException(sprintf('The "%s" transport DSN must contain a host.', $dsn));
        }

        $scheme = $parsedDsn['scheme'];
        $host = $parsedDsn['host'];
        $port = $parsedDsn['port'] ?? null;
        $user = isset($parsedDsn['user']) ? urldecode($parsedDsn['user']) : null;
        $password = isset($parsedDsn['pass']) ? urldecode($parsedDsn['pass']) : null;
        $path = $parsedDsn['path'] ?? null;
        parse_str($parsedDsn['query'] ?? '', $query);

        return new self($scheme, $host, $port, $user, $password, $path, $query);
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return null|int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getQueryValue(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }
}
