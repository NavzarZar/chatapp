<?php

use Slim\App;

use App\Controller\UserController;
use App\Controller\GroupController;
use App\Controller\MessageController;

use App\Middleware\AuthMiddleware;


return function (App $app) {
    $container = $app->getContainer();

    // Authorized
    $app->group('/api', function ($group) use ($container) {
        $groupController = $container->get(GroupController::class);
        $messageController = $container->get(MessageController::class);

        $group->post('/groups', [$groupController, 'createGroup']);
        $group->get('/groups/users/{group_id}', [$groupController, 'getUsersFromGroup']);
        $group->post('/groups/join/{group_id}', [$groupController, 'joinGroup']);

        // Send message
        $group->post('/groups/{group_id}/messages', [$messageController, 'sendMessage']);
        // Get messages from group
        $group->get('/groups/{group_id}/messages', [$messageController, 'getGroupMessages']);
        // Leave group
        $group->delete('/groups/leave/{group_id}', [$groupController, 'leaveGroup']);
    })->add($container->get(AuthMiddleware::class));

    // No need for authorization
    $app->group('/api', function ($group) use ($container) {

        $userController = $container->get(UserController::class);
        $groupController = $container->get(GroupController::class);

        $group->post('/session', [$userController, 'createSession']);
        $group->get('/groups', [$groupController, 'listGroups']);
    });
};
