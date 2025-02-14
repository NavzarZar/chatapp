<?php

use DI\ContainerBuilder;

use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;
use App\Repository\GroupRepository;
use App\Repository\GroupRepositoryImpl;

use App\Service\UserService;
use App\Service\UserServiceImpl;
use App\Service\GroupService;
use App\Service\GroupServiceImpl;



require __DIR__ . '/../vendor/autoload.php';

// Use the existing database configuration for modularity
$config = require __DIR__ . '/database_config.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
        PDO::class => function() use ($config) {
            // Get the path from configuration
            $path = $config['database_path'];

            echo "Connecting to database at $path\n";

            // Throw exception if file does not exist
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


        // SERVICE BINDINGS
        // User
        UserService::class => DI\autowire(UserServiceImpl::class),
        UserServiceImpl::class => DI\autowire(),
        // Group
        GroupService::class => DI\autowire(GroupServiceImpl::class),
        GroupServiceImpl::class => DI\autowire(),
    ]
);

try {
    return $containerBuilder->build();
} catch (Exception $e) {
    echo "Failed to build container: " . $e->getMessage();
    exit(1);
}
