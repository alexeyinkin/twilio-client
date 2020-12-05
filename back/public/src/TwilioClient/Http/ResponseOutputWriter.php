<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Psr\Http\Message\ResponseInterface;

class ResponseOutputWriter
{
    public function write(ResponseInterface $response): void
    {
        header(
            'HTTP/' . $response->getProtocolVersion() . ' ' .
            $response->getStatusCode() . ' ' .
            $response->getReasonPhrase()
        );

        foreach ($response->getHeaders() as $name => $headers) {
            foreach ($headers as $i => $header) {
                header($name . ': ' . $header, $i === 0);
            }
        }

        print $response->getBody()->getContents();
    }
}
