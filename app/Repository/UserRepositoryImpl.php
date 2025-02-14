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

        return $data ? new User($data['id'], $data['username'], $data['token'], $data['token_expiry']) : null;
    }

    public function save(User $user) : User
    {
        // Insert user into database
        $stmt = $this->pdo->prepare("INSERT INTO user (username, token, token_expiry) VALUES (:username, :token, :token_expiry)");
        $stmt->execute([
            'username' => $user->getUsername(),
            'token' => $user->getToken(),
            'token_expiry' => $user->getTokenExpiry()
        ]);

        $newUserId = (int) $this->pdo->lastInsertId();

        return new User($newUserId, $user->getUsername(), $user->getToken(), $user->getTokenExpiry());
    }

    public function findByUsername(string $username) : ?User
    {
        // Query parameterized to prevent SQL injection, find user by username
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['id'], $data['username'], $data['token'], $data['token_expiry']) : null;
    }

    public function updateToken(int $userId, string $newToken, string $expiresAt): void
    {
        // Update user token
        $stmt = $this->pdo->prepare("UPDATE user SET token = :token, token_expiry = :expiresAt WHERE id = :id");
        $stmt->execute([
            'token' => $newToken,
            'token_expiry' => $expiresAt,
            'id' => $userId
        ]);
    }

    public function findByToken(string $token): ?User
    {
        // Get user by token
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE token = :token");

        $stmt->execute(['token' => $token]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['id'], $data['username'], $data['token'], $data['token_expiry']) : null;
    }

    public function updateTokenExpiry(int $userId, string $expiresAt): void
    {
        $stmt = $this->pdo->prepare("UPDATE user SET token_expiry = :expiresAt WHERE id = :id");
        $stmt->execute([
            'expiresAt' => $expiresAt,
            'id' => $userId
        ]);
    }
}
