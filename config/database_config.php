<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load the environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Make sure that if APP_ENV is set to test, we only use the test database
$databasePath = ($_ENV['APP_ENV'] === 'test')
    ? '/data/test.db'
    : $_ENV['DATABASE_PATH'];

return [
     'database_path' => $databasePath // Path to the test db
];