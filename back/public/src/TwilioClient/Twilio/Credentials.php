<?php
declare(strict_types=1);

namespace TwilioClient\Twilio;

class Credentials
{
    private string $accountSid;
    private string $apiKeySid;
    private string $apiKeyToken;

    public function __construct(string $accountSid, string $apiKeySid, string $apiKeyToken)
    {
        $this->accountSid = $accountSid;
        $this->apiKeySid = $apiKeySid;
        $this->apiKeyToken = $apiKeyToken;
    }

    public function getAccountSid(): string
    {
        return $this->accountSid;
    }

    public function getApiKeySid(): string
    {
        return $this->apiKeySid;
    }

    public function getApiKeyToken(): string
    {
        return $this->apiKeyToken;
    }
}
