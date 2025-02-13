<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load the environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get the set environment or default to test.
$databasePath = (__DIR__ . '/../' . $_ENV['DATABASE_PATH']) ?? '/data/test.db';

return [
     'database_path' => $databasePath // Path to the test db
];