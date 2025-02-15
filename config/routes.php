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

        // Create group
        $group->post('/groups', [$groupController, 'createGroup']);

        // Get all users in specific group (must be in that group)
        $group->get('/groups/users/{group_id}', [$groupController, 'getUsersFromGroup']);

        // Join a group
        $group->post('/groups/join/{group_id}', [$groupController, 'joinGroup']);

        // Leave group
        $group->delete('/groups/leave/{group_id}', [$groupController, 'leaveGroup']);

        // Send message
        $group->post('/groups/{group_id}/messages', [$messageController, 'sendMessage']);

        // Get messages from group
        $group->get('/groups/{group_id}/messages', [$messageController, 'getGroupMessages']);

        // Delete message from group, only the user who sent the message can delete it
        $group->delete('/groups/{group_id}/messages/{message_id}', [$messageController, 'deleteMessage']);
    })->add($container->get(AuthMiddleware::class));

    // No need for authorization
    $app->group('/api', function ($group) use ($container) {
        $userController = $container->get(UserController::class);
        $groupController = $container->get(GroupController::class);

        // Get or update token on a specific username
        $group->post('/session', [$userController, 'createSession']);

        // Get all groups
        $group->get('/groups', [$groupController, 'listGroups']);
    });
};
