<?php

require(__DIR__.'/../../vendor/autoload.php');

use Database\Migrations\CreateTables;
use Database\Migrations\PopulateDatabase;


// Load the environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->load();


$databasePath =$_ENV['DATABASE_PATH'];

// Run the migration
$migration = new \Database\Migrations\CreateTables($databasePath);
$migration->up();
