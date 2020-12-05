<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Exception;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class StringStream implements StreamInterface
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function close(): void
    {
        $this->content = '';
    }

    public function detach()
    {
        return null;
    }

    public function getSize(): int
    {
        return mb_strlen($this->content, '8bit');
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return $this->getSize() === 0;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new RuntimeException('This stream is not seekable for the sake of simplicity');
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        throw new RuntimeException('This stream is not writeable for the sake of simplicity');
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return mb_substr($this->content, 0, $length, '8bit');
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function getMetadata($key = null)
    {
        throw new Exception('Not implemented'); // Required to implement too many keys.
    }
}
