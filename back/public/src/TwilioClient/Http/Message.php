<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    private StreamInterface $body;
    private string $protocolVersion;

    /** @var array<int, array<int, string>> */
    private array $headers;

    public function __construct(StreamInterface $body, string $protocolVersion, array $headers)
    {
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): self
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        $lowercaseHeaders = array_change_key_case($this->headers);
        return array_key_exists($name, $lowercaseHeaders);
    }

    public function getHeader($name): array
    {
        $lowercaseHeaders = array_change_key_case($this->headers);
        return $lowercaseHeaders[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        $headerValues = $this->getHeader($name);
        return count($headerValues) > 0 ? implode(',', $headerValues) : '';
    }

    public function withHeader($name, $value): self
    {
        $clone = clone $this;

        $currentCaseName = $this->getHeaderCurrentCaseName($name);

        if ($currentCaseName !== null && $currentCaseName !== $name) { // Keep header order if name is in the same case.
            unset($clone->headers[$currentCaseName]);
        }

        $clone->headers[$name] = self::toArrayOfValues($value);
        return $clone;
    }

    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;

        $currentCaseName = $this->getHeaderCurrentCaseName($name) ?? $name;

        $clone->headers[$currentCaseName] = array_merge(
            $clone->headers[$currentCaseName] ?? [],
            self::toArrayOfValues($value),
        );

        return $clone;
    }

    public function withoutHeader($name): self
    {
        $clone = clone $this;

        $currentCaseName = $this->getHeaderCurrentCaseName($name);

        if ($currentCaseName !== null) {
            unset($clone->headers[$currentCaseName]);
        }

        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    private function getHeaderCurrentCaseName(string $name): ?string
    {
        foreach ($this->headers as $currentCaseName => $header) {
            if (mb_strtolower($currentCaseName) === mb_strtolower($name)) {
                return $currentCaseName;
            }
        }

        return null;
    }

    /**
     * @param $value string|string[]
     * @return array<int, string>
     */
    private static function toArrayOfValues($value): array
    {
        return is_array($value) ? array_values($value) : [$value];
    }
}
