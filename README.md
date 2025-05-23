# Chat Application Backend

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
DATABASE_PATH=data/your_database.db
```
The **`DATABASE_PATH` must be a relative path** from the root directory, without a leading slash.

It should not be called `test.db` as it is used for testing purposes.

Also, there should be an existent your_database.db file in the path specified.
Of course, you can change the name of the database file.

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
IMPORTANT: tests remove all data from the test database

IMPORTANT: tests are set up to always run on `test.db`

IMPORTANT: `test.db` needs to exist in data/ directory, which should be in root.
- data/test.db

This will run tests for **services, and repositories** to ensure expected behavior.


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
After obtaining a token, make sure to include it as Authorization Bearer token.
Tokens expire after an hour.

Also, in order to remove error displaying, set the displayErrorDetails argument to false in public/index.php' in the error middleware constructor.

| Method | Endpoint       | Description                                                                     | Auth Required |
|--------|--------------|---------------------------------------------------------------------------------|--------------|
| `POST` | `/api/session` | Creates or updates a session for a user, returns an authentication token. (1hr) | No |


---

### Groups

| Method | Endpoint                       | Description                                                            | Auth Required |
|--------|--------------------------------|------------------------------------------------------------------------|--------------|
| `POST` | `/api/groups`                  | Creates a group. The creator is automatically added.                   | Yes |
| `GET`  | `/api/groups`                  | Lists all available groups.                                            | No |
| `GET`  | `/api/groups/users/{group_id}` | Retrieves all users in a specific group. User must be in the group.    | Yes |
| `POST` | `/api/groups/join/{group_id}`  | Adds the authenticated user to a group.                                | Yes |
| `DELETE` | `/api/groups/leave/{group_id}` | Removes the authenticated user from a group. User must be in the group | Yes |


---

### Messages

| Method | Endpoint                          | Description                                             | Auth Required |
|--------|----------------------------------|---------------------------------------------------------|--------------|
| `POST` | `/api/groups/{group_id}/messages` | Sends a message in a group. User must be in group.      | Yes |
| `GET`  | `/api/groups/{group_id}/messages` | Retrieves messages from a group. User must be in group. | Yes |
| `DELETE` | `/api/groups/{group_id}/messages/{message_id}` | Deletes a message (only sender can delete).             | Yes |



---
Enjoy!
---