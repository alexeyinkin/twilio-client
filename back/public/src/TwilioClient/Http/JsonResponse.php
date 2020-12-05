<?php
declare(strict_types=1);

namespace TwilioClient\Http;

class JsonResponse extends StreamResponse
{
    public function __construct($data, string $protocolVersion, int $code = ResponseCodeEnum::OK)
    {
        parent::__construct(
            new StringStream(json_encode($data)),
            $protocolVersion,
            $code,
        );
    }
}
