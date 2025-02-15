<?php

use DI\ContainerBuilder;


// Repos
use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;
use App\Repository\GroupRepository;
use App\Repository\GroupRepositoryImpl;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryImpl;
use App\Repository\GroupUserRepository;
use App\Repository\GroupUserRepositoryImpl;

// Services
use App\Service\UserService;
use App\Service\UserServiceImpl;
use App\Service\GroupService;
use App\Service\GroupServiceImpl;
use App\Service\MessageService;
use App\Service\MessageServiceImpl;
use App\Service\GroupUserService;
use App\Service\GroupUserServiceImpl;

// Controllers
use App\Controller\UserController;
use App\Controller\GroupController;
use App\Controller\MessageController;

// Middleware
use App\Middleware\AuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Use the existing database configuration for modularity
$config = require __DIR__ . '/database_config.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
        PDO::class => function() use ($config) {
            // Get the path from configuration
            $path = __DIR__ . "/../" . $config['database_path'];



            if (!file_exists($path)) {
            throw new Exception("Database file not found: $path");
        }
            try {
            // Create the PDO instance
            $pdo = new PDO("sqlite:$path", null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return $pdo;

        } catch (PDOException $e) {
            throw new Exception("Failed to connect to the database: " . $e->getMessage());
        }
        },

        // REPOSITORY BINDINGS
        // User
        UserRepository::class => DI\autowire(UserRepositoryImpl::class),
        UserRepositoryImpl::class => DI\autowire(),
        // Group
        GroupRepository::class => DI\autowire(GroupRepositoryImpl::class),
        GroupRepositoryImpl::class => DI\autowire(),
        // Message
        MessageRepository::class => DI\autowire(MessageRepositoryImpl::class),
        MessageRepositoryImpl::class => DI\autowire(),
        // GroupUserService
        GroupUserRepository::class => DI\autowire(GroupUserRepositoryImpl::class),
        GroupUserRepositoryImpl::class => DI\autowire(),


        // SERVICE BINDINGS
        // User
        UserService::class => DI\autowire(UserServiceImpl::class),
        UserServiceImpl::class => DI\autowire(),
        // Group
        GroupService::class => DI\autowire(GroupServiceImpl::class),
        GroupServiceImpl::class => DI\autowire(),
        // Message
        MessageService::class => DI\autowire(MessageServiceImpl::class),
        MessageServiceImpl::class => DI\autowire(),
        // GroupUserService
        GroupUserService::class => DI\autowire(GroupUserServiceImpl::class),
        GroupUserServiceImpl::class => DI\autowire(),


        // CONTROLLER BINDINGS
        // User
        UserController::class => DI\autowire(),
        GroupController::class => DI\autowire(),
        MessageController::class => DI\autowire(),


        // MIDDLEWARE BINDINGS
        AuthMiddleware::class => DI\autowire(AuthMiddleware::class),
    ]
);

try {
    return $containerBuilder->build();
} catch (Exception $e) {
    echo "Failed to build container: " . $e->getMessage();
    exit(1);
}
