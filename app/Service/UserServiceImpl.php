<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Model\User;


class UserServiceImpl implements UserService {
    private UserRepository $userRepository;

    private $tokenBytes = 32;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function createOrGetSession(string $username) : User
    {
        // Check if user already exists
        $existingUser = $this->userRepository->findByUsername($username);


        // Generate random token
        $token = bin2hex(random_bytes($this->tokenBytes));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hr expiration

        if ($existingUser) {
            $this->userRepository->updateToken($existingUser->getId(), $token, $expiresAt);
            return new User($existingUser->getId(), $username, $token, $expiresAt);
        }

        // Create a new user otherwise
        $user = new User(null, $username, $token, $expiresAt);
        return $this->userRepository->save($user);
    }

    public function authenticateByToken(string $token): ?User
    {
        $user = $this->userRepository->findByToken($token);
        if ($user === null) {
            return null;
        }

        // Check if token has expired
        $currentTime = time();
        $tokenExpiry = strtotime($user->getTokenExpiry());

        if ($currentTime > $tokenExpiry) {
            return null;
        }

        $newExpiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->userRepository->updateTokenExpiry($user->getId(), $newExpiresAt);

        return new User($user->getId(), $user->getUsername(), $user->getToken(), $newExpiresAt);
    }

    public function updateTokenExpiry(int $userId, string $newExpiry)
    {
        $this->userRepository->updateTokenExpiry($userId, $newExpiry);
    }
}
