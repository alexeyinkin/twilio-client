<?php
declare(strict_types=1);

namespace TwilioClient\Exception;

use Exception;

abstract class AbstractHttpException extends Exception
{
    abstract public function getStatusCode(): int;
}
