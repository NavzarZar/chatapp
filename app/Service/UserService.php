<?php

namespace App\Service;

use App\Model\User;

interface UserService {
    public function getUserById(int $id);
    public function createOrGetSession(string $username) : User;
    public function authenticateByToken(string $token) : ?User;
}