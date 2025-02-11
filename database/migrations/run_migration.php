<?php

require(__DIR__.'/../../vendor/autoload.php');


use Database\Migrations\CreateTables;

// Run the migration
$migration = new CreateTables();
$migration->up();
