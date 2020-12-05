<?php
declare(strict_types=1);

namespace TwilioClient\Twilio;

class TwilioCallEventEnum
{
    public const INITIATED = 'initiated';
    public const RINGING = 'ringing';
    public const ANSWERED = 'answered';
    public const COMPLETED = 'completed';

    public const ALL = [
        self::INITIATED,
        self::RINGING,
        self::ANSWERED,
        self::COMPLETED,
    ];
}
