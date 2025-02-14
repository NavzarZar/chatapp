<?php

namespace Database\Migrations;

use SQLite3;

class CreateTables {
    private string $databasePath;

    public function __construct(string $databasePath) {
        $this->databasePath = realpath(__DIR__ . "/../../" . $databasePath);
    }

    public function up()
    {
        $db = new SQLite3($this->databasePath);

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
            UNIQUE(user_id, group_id, content, timestamp) -- Ensure unique messages
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


        echo "Database tables created successfully\n";
    }
}