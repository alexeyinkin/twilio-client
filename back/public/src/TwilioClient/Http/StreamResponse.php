<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamResponse extends Message implements ResponseInterface
{
    private int $statusCode         = ResponseCodeEnum::OK;
    private string $reasonPhrase    = ResponseReasonPhraseEnum::BY_CODE[ResponseCodeEnum::OK];

    public function __construct(StreamInterface $data, string $protocolVersion, int $code = ResponseCodeEnum::OK)
    {
        parent::__construct(
            $data,
            $protocolVersion,
            [],
        );

        $this->statusCode = $code;
        $this->setAutoReasonPhrase();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase;
        $clone->setAutoReasonPhraseIfEmpty();
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function setAutoReasonPhraseIfEmpty(): void
    {
        if ($this->reasonPhrase === '' && array_key_exists($this->statusCode, ResponseReasonPhraseEnum::BY_CODE)) {
            $this->setAutoReasonPhrase();
        }
    }

    private function setAutoReasonPhrase(): void
    {
        $this->reasonPhrase = ResponseReasonPhraseEnum::BY_CODE[$this->statusCode];
    }
}
