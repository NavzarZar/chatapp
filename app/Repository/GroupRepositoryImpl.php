<?php

namespace App\Repository;

use App\Model\Group;
use PDO;

class GroupRepositoryImpl implements GroupRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
       $this->pdo = $pdo;
    }

    public function findById(int $id): ?Group
    {
        // Find group by id
        $stmt = $this->pdo->prepare("SELECT * FROM chat_group WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Group($data['id'], $data['name']) : null;
    }

    public function update(Group $group): Group
    {
        // Update group name
        $stmt = $this->pdo->prepare("UPDATE chat_group SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $group->getName(), 'id' => $group->getId()]);

        return $group;
    }

    public function delete(int $id): void
    {
        // Delete group by id
        $stmt = $this->pdo->prepare("DELETE FROM chat_group WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findAll(): array
    {
        // Find all groups
        $stmt = $this->pdo->query("SELECT * FROM chat_group");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through each group and create a new Group object
        $groups = [];
        foreach ($data as $group) {
            $groups[] = new Group($group['id'], $group['name']);
        }

        return $groups;
    }

    public function findByName(string $name): ?Group
    {
        // Find group by name
        $stmt = $this->pdo->prepare("SELECT * FROM chat_group WHERE name = :name");
        $stmt->execute(['name' => $name]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Group($data['id'], $data['name']) : null;
    }

    public function save(Group $group): Group
    {
        // Insert group into database
        $stmt = $this->pdo->prepare("INSERT INTO chat_group (name) VALUES (:name)");
        $stmt->execute(['name' => $group->getName()]);

        $newGroupId = (int) $this->pdo->lastInsertId();

        return new Group($newGroupId, $group->getName());
    }
}
