<?php
declare(strict_types=1);

namespace TwilioClient\Http;

class RawResponse extends StreamResponse
{
    public function __construct(string $data, string $protocolVersion, int $code = ResponseCodeEnum::OK)
    {
        parent::__construct(
            new StringStream($data),
            $protocolVersion,
            $code,
            );
    }
}