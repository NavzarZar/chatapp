<?php

namespace Database\Migrations;

use SQLite3;

class PopulateDatabase {
    private string $databasePath;

    public function __construct(string $databasePath)
    {
        $this->databasePath = realpath(__DIR__ . "/../../" . $databasePath);
    }

    public function up()
    {
        $db = new SQLite3($this->databasePath);


        // Clear the database
        $db->exec("DROP TABLE IF EXISTS user");
        $db->exec("DROP TABLE IF EXISTS chat_group");
        $db->exec("DROP TABLE IF EXISTS message");
        $db->exec("DROP TABLE IF EXISTS group_user");

        // Create 'user' table

// Create 'user' table
        $db->exec("CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            token TEXT UNIQUE NOT NULL,
            token_expiry DATETIME
        )");

        // Create 'chat_group' table
        $db->exec("CREATE TABLE IF NOT EXISTS chat_group (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        )");

        // Create 'message' table
        $db->exec("CREATE TABLE IF NOT EXISTS message (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            group_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES user (id),
            FOREIGN KEY (group_id) REFERENCES chat_group (id)
            UNIQUE (user_id, group_id, content, timestamp) -- Ensure unique messages
        )");


        // Create table 'group-user' since it's a many-to-many relationship.
        $db->exec("CREATE TABLE IF NOT EXISTS group_user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            group_id INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user (id),
            FOREIGN KEY (group_id) REFERENCES chat_group (id)
            UNIQUE (user_id, group_id) -- Ensure unique pairs of user and group.
        )");


        // Insert some users
        $db->exec("INSERT INTO user (username, token, token_expiry) VALUES ('alice', 'alice_token', '2100-01-01 00:00:00')");
        $db->exec("INSERT INTO user (username, token, token_expiry) VALUES ('bob', 'bob_token', '2100-01-01 00:00:00')");
        $db->exec("INSERT INTO user (username, token, token_expiry) VALUES ('charlie', 'charlie_token', '2100-01-01 00:00:00')");

        // Insert some chat groups
        $db->exec("INSERT INTO chat_group (name) VALUES ('group1')");
        $db->exec("INSERT INTO chat_group (name) VALUES ('group2')");
        $db->exec("INSERT INTO chat_group (name) VALUES ('group3')");

        // Insert some messages
        $db->exec("INSERT INTO message (user_id, group_id, content, timestamp) VALUES (1, 1, 'Hello', '2021-01-01 00:00:00')");
        $db->exec("INSERT INTO message (user_id, group_id, content, timestamp) VALUES (2, 1, 'Hi', '2021-01-01 00:00:00')");
        $db->exec("INSERT INTO message (user_id, group_id, content, timestamp) VALUES (3, 1, 'Hey', '2021-01-01 00:00:00')");

        // Insert some group-user relationships
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (1, 1)");
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (2, 1)");
        $db->exec("INSERT INTO group_user (user_id, group_id) VALUES (3, 1)");

        echo "Database populated successfully\n";
    }
}
