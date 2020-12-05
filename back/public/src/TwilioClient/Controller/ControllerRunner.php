<?php
declare(strict_types=1);

namespace TwilioClient\Controller;

use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TwilioClient\Exception\NotFoundException;

class ControllerRunner
{
    /** @var array<string, AbstractController> */
    private array $map;

    /** @var array<string, int|string> */
    private array $config;

    /**
     * @param array<string, int|string> $config
     * @param PDO $connection
     */
    public function __construct(array $config, PDO $connection)
    {
        $this->map = [
            'api/'                                          => new ApiController($config, $connection),
            'webhook/' . $config['twilioWebhookKey'] . '/'  => new WebhookController($config, $connection),
        ];
    }

    public function runControllerMethod(RequestInterface $request): ResponseInterface
    {
        $request->getUri()->getPath();
        $path = $request->getRequestTarget();
        $controllerPrefix = $this->getControllerPrefix($path);

        if ($controllerPrefix === null) {
            throw new NotFoundException();
        }

        $method = mb_substr($path, mb_strlen($controllerPrefix));
        $controller = $this->map[$controllerPrefix];

        $response = $controller->runMethod($request, $method);
        $response = $response->withAddedHeader('Access-Control-Allow-Origin', '*');
        return $response;
    }

    private function getControllerPrefix(string $path): ?string
    {
        foreach ($this->map as $prefix => $controller) {
            if (mb_substr($path, 0, mb_strlen($prefix)) === $prefix) {
                return $prefix;
            }
        }

        return null;
    }
}
