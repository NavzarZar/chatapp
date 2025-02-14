<?php

namespace App\Model;

class GroupUser {
    private ?int $id;
    private int $userId;
    private int $groupId;

    public function __construct(?int $id, int $userId, int $groupId) {
        $this->id = $id;
        $this->userId = $userId;
        $this->groupId = $groupId;
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

    public function setUserId(?int $id) {
        $this->userId = $id;
    }

    public function setGroupId(int $groupId) {
        $this->groupId = $groupId;
    }
}
