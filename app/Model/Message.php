<?php

namespace App\Model;

class Message {
    // Possibly null since we will usually not provide id in constructor
    private ?int $id;
    private int $userId;
    private int $groupId;
    private string $content;
    private ?string $timestamp;

    public function __construct(?int $id, int $userId, int $groupId, string $content, ?string $timestamp) {
        $this->id = $id;
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->content = $content;
        $this->timestamp = $timestamp;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getGroupId(): int {
        return $this->groupId;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getTimestamp(): string {
        return $this->timestamp;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function setTimestamp(string $timestamp) {
        $this->timestamp = $timestamp;
    }
}
