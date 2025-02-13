<?php

namespace Database\Migrations;

use SQLite3;
use Database\Migrations\CreateTables;

class PopulateDatabase {
    public function __construct(string $databasePath)
    {
        $this->databasePath = $databasePath;
    }

    public function up()
    {
        $db = new SQLite3($this->databasePath);

        // Clear the database
        $db->exec("DROP TABLE IF EXISTS user");
        $db->exec("DROP TABLE IF EXISTS chat_group");
        $db->exec("DROP TABLE IF EXISTS message");
        $db->exec("DROP TABLE IF EXISTS group_user");

        // Create the tables
        $createTables = new CreateTables($this->databasePath);
        $createTables->up();

        // Insert some users
        $db->exec("INSERT INTO user (username, token) VALUES ('alice', 'alice_token')");
        $db->exec("INSERT INTO user (username, token) VALUES ('bob', 'bob_token')");
        $db->exec("INSERT INTO user (username, token) VALUES ('charlie', 'charlie_token')");

        // Insert some chat groups
        $db->exec("INSERT INTO chat_group (name) VALUES ('group1')");
        $db->exec("INSERT INTO chat_group (name) VALUES ('group2')");
        $db->exec("INSERT INTO chat_group (name) VALUES ('group3')");

        // Insert some messages
        $db->exec("INSERT INTO message (user_id, group_id, message) VALUES (1, 1, 'Hello')");
        $db->exec("INSERT INTO message (user_id, group_id, message) VALUES (2, 1, 'Hi')");
        $db->exec("INSERT INTO message (user_id, group_id, message) VALUES (3, 1, 'Hey')");

        // Insert some group-user relationships
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (1, 1)");
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (2, 1)");
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (3, 1)");

        echo "Database populated successfully\n";
    }
}
