<?php

namespace Database\Migrations;

use SQLite3;

class DeleteAllTables {
    private string $databasePath;

    public function __construct(string $databasePath) {
        $this->databasePath = __DIR__ . "/../.." . $databasePath;
    }

    public function up() {
        $db = new SQLite3($this->databasePath);

        // Clear the database
        $db->exec("DROP TABLE IF EXISTS user");
        $db->exec("DROP TABLE IF EXISTS chat_group");
        $db->exec("DROP TABLE IF EXISTS message");
        $db->exec("DROP TABLE IF EXISTS group_user");

        echo "All tables deleted successfully\n";
    }


}