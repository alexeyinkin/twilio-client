<?php
declare(strict_types=1);

namespace TwilioClient\Exception;

use TwilioClient\Http\ResponseCodeEnum;

class NotFoundException extends AbstractHttpException
{
    public function getStatusCode(): int
    {
        return ResponseCodeEnum::NOT_FOUND;
    }
}
