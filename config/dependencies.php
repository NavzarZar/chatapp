<?php

use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

// Use the existing database configuration for modularity
$config = require __DIR__ . '/database_config.php';

$container->set(PDO::class, function() use ($config) {
    // Get the path from configuration
    $path = $config['database_path'];

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

});
