<?php

namespace App\Repository;

use PDO;
use App\Model\User;

class UserRepositoryImpl implements UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id) : ?User
    {
        // Query parameterized to prevent SQL injection, find user by id
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['id'], $data['username'], $data['token']) : null;
    }

    public function save(User $user) : User
    {
        // Insert user into database
        $stmt = $this->pdo->prepare("INSERT INTO user (username, token) VALUES (:username, :token)");
        $stmt->execute(['username' => $user->getUsername(), 'token' => $user->getToken()]);

        $newUserId = (int) $this->pdo->lastInsertId();

        return new User($newUserId, $user->getUsername(), $user->getToken());
    }
}
