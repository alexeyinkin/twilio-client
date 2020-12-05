<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Psr\Http\Message\RequestInterface;

class RequestFactory
{
    private const DEFAULT_PROTOCOL_VERSION = '1.0';

    private RequestInterface $currentRequest;
    private bool $currentRequestCreated = false;

    public function getCurrentRequest(): RequestInterface
    {
        if (!$this->currentRequestCreated) {
            $this->currentRequest = $this->createCurrentRequest();
        }

        return $this->currentRequest;
    }

    private function createCurrentRequest(): RequestInterface
    {
        return new Request(
            new StringStream(''),
            self::getCurrentProtocolVersion(),
            [],
            new Uri($_GET['path']), // Comes from URL rewriting, see .htaccess file.
        );
    }

    private static function getCurrentProtocolVersion(): string
    {
        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? '';

        if (preg_match('~^HTTP/(\\d\\.\\d)$~', $protocol, $matches)) {
            return $matches[1];
        }

        return self::DEFAULT_PROTOCOL_VERSION;
    }
}
