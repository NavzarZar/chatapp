<?php

namespace App\Repository;

use PDO;
use App\Model\GroupUser;
use App\Model\User;

class GroupUserRepositoryImpl implements GroupUserRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?GroupUser
    {
        $stmt = $this->pdo->prepare("SELECT * FROM group_user WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new GroupUser($data['id'], $data['user_id'], $data['group_id']) : null;
    }

    public function save(GroupUser $userGroup): GroupUser
    {
        $stmt = $this->pdo->prepare("INSERT INTO group_user (user_id, group_id) VALUES (:user_id, :group_id)");
        $stmt->execute([
            'user_id' => $userGroup->getUserId(),
            'group_id' => $userGroup->getGroupId()
        ]);

        $newUserGroupId = (int) $this->pdo->lastInsertId();

        return new GroupUser($newUserGroupId, $userGroup->getUserId(), $userGroup->getGroupId());
    }

    public function update(GroupUser $userGroup): GroupUser
    {
        $stmt = $this->pdo->prepare("UPDATE group_user SET user_id = :user_id, group_id = :group_id WHERE id = :id");
        $stmt->execute([
            'user_id' => $userGroup->getUserId(),
            'group_id' => $userGroup->getGroupId(),
            'id' => $userGroup->getId()
        ]);

        return $userGroup;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM group_user WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM group_user");
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userGroups = [];
        foreach ($data as $userGroup) {
            $userGroups[] = new GroupUser($userGroup['id'], $userGroup['user_id'], $userGroup['group_id']);
        }

        return $userGroups;
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM group_user WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userGroups = [];
        foreach ($data as $userGroup) {
            $userGroups[] = new GroupUser($userGroup['id'], $userGroup['user_id'], $userGroup['group_id']);
        }

        return $userGroups;
    }

    public function findByGroupId(int $groupId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM group_user WHERE group_id = :group_id");
        $stmt->execute(['group_id' => $groupId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userGroups = [];
        foreach ($data as $userGroup) {
            $userGroups[] = new GroupUser($userGroup['id'], $userGroup['user_id'], $userGroup['group_id']);
        }

        return $userGroups;
    }

    public function getUsersFromGroup(int $groupId) : array
    {
        // Get users from specific group, join with user table to get all users, return array of users
        $stmt = $this->pdo->prepare("SELECT user.id, user.username, user.token, user.token_expiry FROM user JOIN group_user ON user.id = group_user.user_id WHERE group_user.group_id = :group_id");

        $stmt->execute(['group_id' => $groupId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return array of User instances
        $users = [];
        foreach ($data as $user) {
            $users[] = new User($user['id'], $user['username'], $user['token'], $user['token_expiry']);
        }

        return $users;
    }
}
