<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;

require dirname(__DIR__) . '/vendor/autoload.php';


$container = require __DIR__ . '/../config/dependencies.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

$customErrorHandler = function (
    Request $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode([
        'error' => 'Not Found',
        'message' => $exception->getMessage()
    ]));

    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
};

// Register routes
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);


// Add error middleware
$errorMiddleware = new ErrorMiddleware(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    true, // set to false when not testing
    false,
    false
);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->add($errorMiddleware);


// Run app
$app->run();