<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Exception;
use Psr\Http\Message\UriInterface;

/**
 * Only use this class to store path for now.
 */
class Uri implements UriInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getScheme(): string
    {
        throw new Exception('Not implemented');
    }

    public function getAuthority(): string
    {
        throw new Exception('Not implemented');
    }

    public function getUserInfo(): string
    {
        throw new Exception('Not implemented');
    }

    public function getHost(): string
    {
        throw new Exception('Not implemented');
    }

    public function getPort(): ?int
    {
        throw new Exception('Not implemented');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        throw new Exception('Not implemented');
    }

    public function getFragment(): string
    {
        throw new Exception('Not implemented');
    }

    public function withScheme($scheme): self
    {
        throw new Exception('Not implemented');
    }

    public function withUserInfo($user, $password = null): self
    {
        throw new Exception('Not implemented');
    }

    public function withHost($host): self
    {
        throw new Exception('Not implemented');
    }

    public function withPort($port): self
    {
        throw new Exception('Not implemented');
    }

    public function withPath($path): self
    {
        throw new Exception('Not implemented');
    }

    public function withQuery($query): self
    {
        throw new Exception('Not implemented');
    }

    public function withFragment($fragment): self
    {
        throw new Exception('Not implemented');
    }

    public function __toString(): string
    {
        return 'Not implemented';
    }
}
