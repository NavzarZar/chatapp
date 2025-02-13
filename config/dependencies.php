<?php

use DI\ContainerBuilder;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;

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
        // Bind the UserRepository interface to the UserRepositoryImpl class
        UserRepository::class => DI\autowire(UserRepositoryImpl::class),

        // Autowire the UserRepositoryImpl class
        UserRepositoryImpl::class => DI\autowire()
    ]
);

try {
    return $containerBuilder->build();
} catch (Exception $e) {
    echo "Failed to build container: " . $e->getMessage();
    exit(1);
}
