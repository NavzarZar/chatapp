# Chat Application Backend

## Project Overview


# Chat Application Backend - Full Setup and Usage Guide

## Project Overview

This is a chat application backend built using **PHP** with the **Slim Framework** and **SQLite**. It follows a **clean architecture** where:

- **Controllers** handle HTTP requests and responses.
- **Services** contain business logic.
- **Repositories** handle database access using **PDO**.
- **Middleware** manages authentication using **Bearer tokens**.

The backend provides **group-based messaging** where users can create, join, and leave groups, send messages, and retrieve message history.

Authentication works based on "sessions" where a user can obtain a **Bearer token** by providing a username. This token is then used to access protected endpoints.

Obviously, this can be changed easily to use a more secure authentication method, unfortunately there were time constraints :)

Practically, every single singleton class has all necessary dependencies injected into its constructor. This makes it easy to test and swap out implementations.

---

## Folder structure
- **app** - contains controllers, middleware, model, services, repo
- **config** - contains configurations such as routes, database location and the DI container setup
- **database** - contains migration scripts
- **public** - contains the index.php file that serves as the entry point for the application
- **tests** - contains unit tests for services, and repositories

---

## API Endpoints

### Authentication
**All endpoints under `/api/` (except `/session` and listing groups) require a Bearer token.**  
To get a token, send a request to `/api/session` with a `username`.

| Method | Endpoint       | Description                                      | Auth Required |
|--------|--------------|--------------------------------------------------|--------------|
| `POST` | `/api/session` | Creates or updates a session for a user, returns an authentication token.  | No |


---

### Groups

| Method | Endpoint                       | Description                                             | Auth Required |
|--------|--------------------------------|--------------------------------------------------------|--------------|
| `POST` | `/api/groups`                  | Creates a group. The creator is automatically added.  | Yes |
| `GET`  | `/api/groups`                  | Lists all available groups.                           | No |
| `GET`  | `/api/groups/users/{group_id}` | Retrieves all users in a specific group.             | Yes |
| `POST` | `/api/groups/join/{group_id}`  | Adds the authenticated user to a group.              | Yes |
| `DELETE` | `/api/groups/leave/{group_id}` | Removes the authenticated user from a group.       | Yes |


---

### Messages

| Method | Endpoint                          | Description                                        | Auth Required |
|--------|----------------------------------|--------------------------------------------------|--------------|
| `POST` | `/api/groups/{group_id}/messages` | Sends a message in a group.                      | Yes |
| `GET`  | `/api/groups/{group_id}/messages` | Retrieves messages from a group.                 | Yes |
| `DELETE` | `/api/groups/{group_id}/messages/{message_id}` | Deletes a message (only sender can delete). | Yes |


---

## Setting Up the Project

### 1. Install Dependencies
Ensure **PHP and Composer** are installed, then run:
```bash
composer install
```

---

### 2. Configure Environment Variables
Create a `.env` file in the project root with the following content:
```
APP_ENV=development
DATABASE_PATH=data/test.db
```
The **`DATABASE_PATH` must be a relative path** from the root directory, without a leading slash.

Also, there should be an existent .db file in the path specified.

---

### 3. Create Database Tables
Run the migration script to generate all tables:
```bash
php database/migrations/run_migration.php
```
This will execute the **CreateTables** migration to create necessary tables in the SQLite database.

Other migrations are available, such as populate the database with some initial data or delete tables.

---

### 4. Start the Server
Run the PHP built-in server and serve from the `public/` directory:
```bash
php -S localhost:8000 -t public/
```
Your API is now accessible at `http://localhost:8000`.

---

### 5. Run Tests
To verify that everything is working correctly, run:
```bash
php vendor/bin/phpunit
```
This will run tests for **controllers, services, and repositories** to ensure expected behavior.

---
Enjoy!
---