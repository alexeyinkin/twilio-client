<?php
declare(strict_types=1);

namespace TwilioClient\Dto;

use JsonSerializable;
use TwilioClient\Entity\Call;
use TwilioClient\Entity\Provider;

class CallResultDto implements JsonSerializable
{
    private Provider $provider;
    private Call $call;

    public function __construct(Provider $provider, Call $call)
    {
        $this->provider = $provider;
        $this->call = $call;
    }

    public function jsonSerialize(): array
    {
        return [
            'provider'  => $this->provider->jsonSerialize(),
            'call'      => $this->call->jsonSerialize(),
        ];
    }
}
