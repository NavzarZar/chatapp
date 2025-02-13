<?php

namespace App\Model;

class User {
    // If possible null since we do not want to assign ids to users (most of the time)
    private ?int $id;
    private string $username;
    private string $token;

    public function __construct(?int $id, string $username, string $token) {
        $this->id = $id;
        $this->username = $username;
        $this->token = $token;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }
}
