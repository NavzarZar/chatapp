<?php

namespace App\Repository;

use App\Model\User;

interface UserRepository
{
    public function findById(int $id) : ?User;
    public function findByUsername(string $username) : ?User;
    public function save(User $user) : User;
    public function updateToken(int $userId, string $newToken, string $expiresAt) : void;
    public function updateTokenExpiry(int $userId, string $expiresAt) : void;
    public function findByToken(string $token) : ?User;

}