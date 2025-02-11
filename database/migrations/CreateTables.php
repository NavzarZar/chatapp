<?php

namespace Database\Migrations;

use SQLite3;

class CreateTables {
    public function up()
    {
        $db = new SQLite3(__DIR__ . '/../chat.db');

        // Create 'user' table
        $db->exec("CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            token TEXT UNIQUE NOT NULL
        )");

        // Create 'chat_group' table
        $db->exec("CREATE TABLE IF NOT EXISTS chat_group (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        )");

        // Create 'message' table
        $db->exec("CREATE TABLE IF NOT EXISTS message (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER UNIQUE NOT NULL,
            group_id INTEGER UNIQUE NOT NULL,
            message TEXT NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES user (id),
            FOREIGN KEY (group_id) REFERENCES chat_group (id)
        )");


        // Create table 'group-user' since it's a many-to-many relationship.
        $db->exec("CREATE TABLE IF NOT EXISTS group_user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER UNIQUE NOT NULL,
            group_id INTEGER UNIQUE NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user (id),
            FOREIGN KEY (group_id) REFERENCES chat_group (id)
            UNIQUE (user_id, group_id) -- Ensure unique pairs of user and group.
        )");


        echo "Database tables created successfully\n";
    }
}