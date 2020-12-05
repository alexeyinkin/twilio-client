<?php
declare(strict_types=1);

namespace TwilioClient\Enum;

class CallStatusEnum
{
    public const CALLING_ADMIN      = 'CALLING_ADMIN';
    public const ADMIN_CONNECTED    = 'ADMIN_CONNECTED';
    public const ADMIN_FAILED       = 'ADMIN_FAILED';
    public const CALLING_PROVIDER   = 'CALLING_PROVIDER';
    public const BOTH_CONNECTED     = 'BOTH_CONNECTED';
    public const PROVIDER_FAILED    = 'PROVIDER_FAILED';
    public const COMPLETED          = 'COMPLETED';
}
