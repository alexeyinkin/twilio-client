<?php
declare(strict_types=1);

namespace TwilioClient\Http;

use Psr\Http\Message\RequestInterface;
use Throwable;
use TwilioClient\Exception\AbstractHttpException;

class ExceptionResponseFactory
{
    public function createResponse(RequestInterface $request, Throwable $ex): RawResponse
    {
        if ($ex instanceof AbstractHttpException) {
            return new RawResponse(
                $ex->getMessage() . ' ' . $ex->getTraceAsString(),
                $request->getProtocolVersion(),
                $ex->getStatusCode(),
            );
        }

        return new RawResponse(
            $ex->getMessage() . ' ' . $ex->getTraceAsString(),
            $request->getProtocolVersion(),
            ResponseCodeEnum::INTERNAL_SERVER_ERROR,
        );
    }
}
