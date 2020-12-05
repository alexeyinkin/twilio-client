<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements ServerRequestInterface
{
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;

    public function __construct(StreamInterface $body, string $protocolVersion, array $headers, UriInterface $uri)
    {
        parent::__construct($body, $protocolVersion, $headers);

        $this->uri = $uri;
        $this->requestTarget = $uri->getPath();
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget): self
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): self
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        throw new Exception('Not implemented');
    }

    public function getServerParams(): array
    {
        return $_SERVER;
    }

    public function getCookieParams(): array
    {
        return $_COOKIE;
    }

    public function withCookieParams(array $cookies): self
    {
        throw new Exception('Not implemented');
    }

    public function getQueryParams(): array
    {
        return $_GET;
    }

    public function withQueryParams(array $query): self
    {
        throw new Exception('Not implemented');
    }

    /**
     * @return UploadedFileInterface[]
     */
    public function getUploadedFiles(): array
    {
        throw new Exception('Not implemented');
    }

    public function withUploadedFiles(array $uploadedFiles): self
    {
        throw new Exception('Not implemented');
    }

    public function getParsedBody()
    {
        throw new Exception('Not implemented');
    }

    public function withParsedBody($data): self
    {
        throw new Exception('Not implemented');
    }

    public function getAttributes(): array
    {
        return $_REQUEST;
    }

    public function getAttribute($name, $default = null)
    {
        return $_REQUEST[$name] ?? $default;
    }

    public function withAttribute($name, $value): self
    {
        throw new Exception('Not implemented');
    }

    public function withoutAttribute($name): self
    {
        throw new Exception('Not implemented');
    }
}
