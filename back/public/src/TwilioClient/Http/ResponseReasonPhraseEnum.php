<?php
declare(strict_types=1);

namespace TwilioClient\Http;

class ResponseReasonPhraseEnum
{
    public const BY_CODE = [
        ResponseCodeEnum::OK                    => 'OK',
        ResponseCodeEnum::NOT_FOUND             => 'Not Found',
        ResponseCodeEnum::INTERNAL_SERVER_ERROR => 'Internal Server Error',
    ];
}
