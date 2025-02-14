# Chat Application Backend

## Project Overview

This is a **chat application backend** built using **PHP** with the **Slim Framework** and **SQLite** for database management. It follows **a clean architecture** with **dependency injection** (DI) for managing services and repositories. Authentication is token-based, and authorization is handled via middleware. User must pass create a session, and use the session token for any authorization-based requests.

### **Technologies Used**

- **PHP** (Slim Framework for routing)
- **SQLite** (Database)
- **PDO** (Database access)
- **PHP-DI** (Dependency Injection)
- **PHPUnit** (Testing)

---

## API Endpoints

### **Authentication & Sessions**

| Method | Endpoint       | Description                                          |
| ------ | -------------- | ---------------------------------------------------- |
| `POST` | `/api/session` | Creates a session for a user (logs in or registers). |

> **Note:** The session endpoint returns a **token** that must be passed as an `Authorization` header for all authorized requests.

### **Groups**

| Method | Endpoint                       | Description                                              |
| ------ | ------------------------------ | -------------------------------------------------------- |
| `POST` | `/api/groups`                  | Creates a new group. The creator is automatically added. **(Requires Authorization Token)** |
| `GET`  | `/api/groups`                  | Lists all available groups.                              |
| `GET`  | `/api/groups/users/{group_id}` | Retrieves users in a group. **(Requires Authorization Token)** |
| `POST` | `/api/groups/join/{group_id}`  | Adds the authenticated user to a group. **(Requires Authorization Token)** |

---

## **Dependency Injection (DI)**

The project uses **PHP-DI** to manage dependencies.

### **Example: Setting Up DI in `dependencies.php`**

```php
use DI\Container;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryImpl;
use App\Service\UserService;
use App\Service\UserServiceImpl;
use PDO;

$container = new Container();

$container->set(PDO::class, function() {
    return new PDO("sqlite:" . __DIR__ . '/../data/database.db', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
});

$container->set(UserRepository::class, DI\autowire(UserRepositoryImpl::class));
$container->set(UserService::class, DI\autowire(UserServiceImpl::class));
```

✔ **Ensures that all services and repositories are injected where needed.**

---

## **Middleware for Authentication**

All protected routes are wrapped inside a **route group with authentication middleware**.

### **Example: Protecting Routes in `routes.php`**

```php
$app->group('/api', function ($group) use ($container) {
    $groupController = $container->get(GroupController::class);
    $group->post('/groups', [$groupController, 'createGroup']);
    $group->get('/groups/users/{group_id}', [$groupController, 'getUsersFromGroup']);
    $group->post('/groups/join/{group_id}', [$groupController, 'joinGroup']);
})->add($container->get(AuthMiddleware::class));
```

✔ **Ensures that only authenticated users can create or join groups.**

> **Authorization:** For all protected routes, the token obtained from `/api/session` must be passed in the request headers as:
> ```
> Key: Authorization | Value: YOUR_TOKEN_HERE
> ```

---

## **Running Tests**

The project includes **unit tests** for services and repositories.

### **Run Tests**

```bash
php vendor/bin/phpunit
```

✔ **Tests automatically reset the database before each run.**

---

## **How to Run the Project**

### **Install Dependencies**

```bash
composer install
```

### **Set Up the Database**

Before running the application, ensure that you have set the **database file path** in your `.env` file:

```env
DATABASE_PATH=data/database.db
```

Then, run:

```bash
php database/migrations/run_migration.php
```
migration inside should be the populate tables one, if not change it.

### **Start the Server**

```bash
php -S localhost:8000 -t public/
```

✔ **Now, the API is running at** `http://localhost:8000`. Enjoy!

--- 

- Chatbots used for some tests and documentation

Enjoy!!! 

