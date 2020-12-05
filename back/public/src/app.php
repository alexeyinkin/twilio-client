<?php
declare(strict_types=1);

use TwilioClient\ConfigReader;
use TwilioClient\Controller\ControllerRunner;
use TwilioClient\Db\ConnectionFactory;
use TwilioClient\Http\ExceptionResponseFactory;
use TwilioClient\Http\RequestFactory;
use TwilioClient\Http\ResponseOutputWriter;

require_once __DIR__ . '/class_loader.php';

// Would have preferred yml but can't use libs.
const CONFIG_FILENAME = __DIR__ . '/../../hidden/config.json';

try {
    $requestFactory = new RequestFactory();
    $request = $requestFactory->getCurrentRequest();

    $configReader = new ConfigReader(CONFIG_FILENAME);
    $config = $configReader->getConfig();
    
    $connectionFactory = new ConnectionFactory($config);
    $connection = $connectionFactory->getConnection();

    $controllerRunner = new ControllerRunner($config, $connection);
    $response = $controllerRunner->runControllerMethod($request);
} catch (Throwable $ex) {
    $exceptionResponseFactory = new ExceptionResponseFactory();
    $response = $exceptionResponseFactory->createResponse($request, $ex);
}

$responseOutputWriter = new ResponseOutputWriter();
$responseOutputWriter->write($response);
