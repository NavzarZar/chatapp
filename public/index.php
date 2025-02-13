<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();

$app->get('/api/groups', function (Request $request, Response $response) {

    $response->getBody()->write("Hello, world!");
    return $response;
});

$app->run();