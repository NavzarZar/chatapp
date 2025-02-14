<?php

use Slim\App;

use App\Controller\UserController;
use App\Controller\GroupController;

use App\Middleware\AuthMiddleware;


return function (App $app) {
    $container = $app->getContainer();

    // Authorized
    $app->group('/api', function ($group) use ($container) {
        $groupController = $container->get(GroupController::class);

        $group->post('/groups', [$groupController, 'createGroup']);
    })->add($container->get(AuthMiddleware::class));

    // No need for authorization
    $app->group('/api', function ($group) use ($container) {

        $userController = $container->get(UserController::class);

        $group->post('/session', [$userController, 'createSession']);
    });
};
