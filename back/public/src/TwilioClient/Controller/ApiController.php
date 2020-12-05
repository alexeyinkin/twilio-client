<?php
declare(strict_types=1);

namespace TwilioClient\Controller;

use PDO;
use Psr\Http\Message\ServerRequestInterface;
use TwilioClient\Exception\NotFoundException;
use TwilioClient\Http\JsonResponse;
use TwilioClient\Service\CallService;
use TwilioClient\Storage\CallStorageService;
use TwilioClient\Storage\ProviderStorageService;
use TwilioClient\Twilio\Credentials;

class ApiController extends AbstractController
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
    public function getProviders(ServerRequestInterface $request): JsonResponse
    {
        $providers = $this->providerStorageService->findAllProviders();
        return new JsonResponse(['data' => $providers], $request->getProtocolVersion());
    }

    /**
     * @Api
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function setProviderStatus(ServerRequestInterface $request): JsonResponse
    {
        $providerId = (int) $request->getAttribute('providerId');
        $status     = (string) $request->getAttribute('status');

        $provider = $this->providerStorageService->findProviderById($providerId);

        if ($provider === null) {
            throw new NotFoundException();
        }

        $this->providerStorageService->setProviderStatus($providerId, $status);
        return new JsonResponse([], $request->getProtocolVersion());
    }

    /**
     * @Api
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function call(ServerRequestInterface $request): JsonResponse
    {
        $providerId = (int) $request->getAttribute('providerId');
        $provider = $this->providerStorageService->findProviderById($providerId);

        if ($provider === null) {
            throw new NotFoundException();
        }

        $credentials = new Credentials(
            $request->getAttribute('accountSid') ?: $this->config['accountSid'],
            $request->getAttribute('apiKeySid') ?: $this->config['apiKeySid'],
            $request->getAttribute('apiKeyToken') ?: $this->config['apiKeyToken'],
        );

        // Normally we would store phone on Provider in the DB but on trial accounts we can only call verified numbers
        // so store it in config for now.
        $providerNumber = $request->getAttribute('providerNumber') ?: $this->config['providerNumber'];
        $twilioNumber   = $request->getAttribute('twilioNumber') ?: $this->config['twilioNumber'];
        $adminNumber    = $request->getAttribute('adminNumber') ?: $this->config['adminNumber'];

        $result = $this->callService->call(
            $credentials,
            $providerId,
            $twilioNumber,
            $adminNumber,
            $providerNumber,
        );

        return new JsonResponse(['data' => $result], $request->getProtocolVersion());
    }

    /**
     * @Api
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function getCall(ServerRequestInterface $request): JsonResponse
    {
        $callSid = $request->getAttribute('sid');
        $call = $this->callStorageService->findCallBySid($callSid);
        return new JsonResponse(['data' => $call], $request->getProtocolVersion());
    }
}
