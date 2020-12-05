<?php
declare(strict_types=1);

namespace TwilioClient\Service;

use DateTimeImmutable;
use TwilioClient\Dto\CallResultDto;
use TwilioClient\Entity\Call;
use TwilioClient\Enum\CallStatusEnum;
use TwilioClient\Storage\CallStorageService;
use TwilioClient\Storage\ProviderStorageService;
use TwilioClient\Twilio\Client;
use TwilioClient\Twilio\Credentials;
use TwilioClient\Twilio\TwilioCallStatusEnum;

class CallService
{
    private const PRIMARY_CALL_STATUS_CALLBACK = 'https://{$appHost}/webhook/{$key}/onPrimaryCallStatusChange';
    private const SUB_CALL_STATUS_CALLBACK = 'https://{$appHost}/webhook/{$key}/onSubCallStatusChange';

    private const TWIML = '
        <Response>
            <Say voice="alice">
                You will now be connected to {$providerName}.
            </Say>
            <Dial action="{$subCallStatusCallback}">
                <Number>{$providerNumber}</Number>
            </Dial>
        </Response>
        ';

    private CallStorageService $callStorageService;
    private ProviderStorageService $providerStorageService;

    /** @var array<string, int|string> */
    private array $config;

    /**
     * @param array<string, int|string> $config
     * @param CallStorageService $callStorageService
     * @param ProviderStorageService $providerStorageService
     */
    public function __construct(
        array $config,
        CallStorageService $callStorageService,
        ProviderStorageService $providerStorageService
    )
    {
        $this->config = $config;
        $this->callStorageService = $callStorageService;
        $this->providerStorageService = $providerStorageService;
    }

    public function call(
        Credentials $credentials,
        int $providerId,
        string $twilioNumber,
        string $adminNumber,
        string $providerNumber
    ): CallResultDto
    {
        $provider = $this->providerStorageService->findProviderById($providerId);

        $client = new Client($credentials);
        $client->setStatusCallback($this->getCallbackUrl(self::PRIMARY_CALL_STATUS_CALLBACK));

        $twiml = str_replace(
            [
                '{$providerName}',
                '{$providerNumber}',
                '{$subCallStatusCallback}',
            ],
            [
                htmlspecialchars($provider->getName()),
                htmlspecialchars($providerNumber),
                htmlspecialchars($this->getCallbackUrl(self::SUB_CALL_STATUS_CALLBACK)),
            ],
            self::TWIML,
        );

        $response = $client->call($twilioNumber, $adminNumber, $twiml);

        $call = new Call();
        $call->setSid((string) $response->Call->Sid[0]);
        $call->setProviderId($providerId);
        $call->setStatus(CallStatusEnum::CALLING_ADMIN);
        $call->setStartDatetime(new DateTimeImmutable());

        $this->callStorageService->saveCall($call);

        return new CallResultDto($provider, $call);
    }

    /**
     * @param string[] $params
     */
    public function onPrimaryCallStatusChange(array $params): void
    {
        $callSid = $params['CallSid'];
        $call = $this->callStorageService->findCallBySid($callSid);

        switch ($params['CallStatus']) {
            case TwilioCallStatusEnum::IN_PROGRESS:
                $call->setStatus(CallStatusEnum::ADMIN_CONNECTED);
                break;

            case TwilioCallStatusEnum::COMPLETED:
                $this->handlePrimaryCallCompleted($call);
                break;
        }

        $this->callStorageService->saveCall($call);
    }

    private function handlePrimaryCallCompleted(Call $call): void
    {
        $newStatus = null;

        switch ($call->getStatus()) {
            case CallStatusEnum::CALLING_ADMIN:
            case CallStatusEnum::ADMIN_CONNECTED:
                $newStatus = CallStatusEnum::ADMIN_FAILED;
                break;

            case CallStatusEnum::CALLING_PROVIDER:
                $newStatus = CallStatusEnum::PROVIDER_FAILED;
                break;

            case CallStatusEnum::BOTH_CONNECTED:
                $newStatus = CallStatusEnum::COMPLETED;
                break;
        }

        if ($newStatus !== null) {
            $call->setStatus($newStatus);
        }

        if ($call->getEndDatetime() === null) {
            // TODO: Use $params['Timestamp'] which is in a form of 'Sun, 08 Nov 2020 10:30:54 +0000',
            //       convert timezone for that.
            $call->setEndDatetime(new DateTimeImmutable());
        }
    }

    /**
     * @param string[] $params
     * @return string
     */
    public function onSubCallStatusChange(array $params): string
    {
        $callSid = $params['CallSid'];
        $call = $this->callStorageService->findCallBySid($callSid);

        switch ($params['DialCallStatus'] ?? '') {
            case TwilioCallStatusEnum::COMPLETED:
                $call->setStatus(CallStatusEnum::COMPLETED);
                break;
            default:
                $call->setStatus(CallStatusEnum::PROVIDER_FAILED);
        }

        // TODO: Use $params['Timestamp'] which is in a form of 'Sun, 08 Nov 2020 10:30:54 +0000',
        //       convert timezone for that.
        $call->setEndDatetime(new DateTimeImmutable());
        $this->callStorageService->saveCall($call);

        return '<Response><Hangup/></Response>';
    }

    private function getCallbackUrl(string $template): string
    {
        return str_replace(
            [
                '{$appHost}',
                '{$key}',
            ],
            [
                $this->config['appHost'],
                $this->config['twilioWebhookKey'],
            ],
            $template,
        );
    }
}
