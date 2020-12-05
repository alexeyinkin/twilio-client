<?php
declare(strict_types=1);

namespace TwilioClient\Controller;

use PDO;
use Psr\Http\Message\ServerRequestInterface;
use TwilioClient\Http\JsonResponse;
use TwilioClient\Http\RawResponse;
use TwilioClient\Service\CallService;
use TwilioClient\Storage\CallStorageService;
use TwilioClient\Storage\ProviderStorageService;

class WebhookController extends AbstractController
{
    private ProviderStorageService $providerStorageService;
    private CallService $callService;
    private CallStorageService $callStorageService;

    /** @var array<string, int|string> */
    private array $config;

    /**
     * @param array<string, int|string> $config
     * @param PDO $connection
     */
    public function __construct(array $config, PDO $connection)
    {
        $this->config = $config;
        $this->providerStorageService = new ProviderStorageService($connection);
        $this->callStorageService = new CallStorageService($connection);
        $this->callService = new CallService($config, $this->callStorageService, $this->providerStorageService);
    }

    /**
     * @Api
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function onPrimaryCallStatusChange(ServerRequestInterface $request): JsonResponse
    {
        $this->callService->onPrimaryCallStatusChange($request->getAttributes());
        return new JsonResponse([], $request->getProtocolVersion());
    }

    /**
     * @Api
     * @param ServerRequestInterface $request
     * @return RawResponse
     */
    public function onSubCallStatusChange(ServerRequestInterface $request): RawResponse
    {
        $twiml = $this->callService->onSubCallStatusChange($request->getAttributes());
        return new RawResponse($twiml, $request->getProtocolVersion());
    }
}
