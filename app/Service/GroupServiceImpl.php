<?php

namespace App\Service;

use App\Model\Group;
use App\Model\GroupUser;
use App\Repository\GroupRepository;
use App\Repository\GroupUserRepository;

class GroupServiceImpl implements GroupService {

    private GroupRepository $groupRepository;
    private GroupUserRepository $groupUserRepository;

    public function __construct(GroupRepository $groupRepository, GroupUserRepository $groupUserRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->groupUserRepository = $groupUserRepository;
    }

    public function getGroupById(int $id)
    {
        return $this->groupRepository->findById($id);
    }

    // Create group creates group but also adds user to group
    public function createGroup(string $name, int $userId): Group
    {
        try {
            $this->groupRepository->save(new Group(null, $name));
            // Add user to group
            $group = $this->groupRepository->findByName($name);
            $this->groupUserRepository->save(new GroupUser(null, $userId, $group->getId()));

            return $group;
        } catch(\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new \PDOException('Group name already exists', 409);
            }

            throw $e;
        }
    }

    public function updateGroup(Group $group): Group
    {
        return $this->groupRepository->update($group);
    }

    public function deleteGroup(int $id): void
    {
        $this->groupRepository->delete($id);
    }

    public function getAllGroups(): array
    {
        return $this->groupRepository->findAll();
    }

    public function getGroupByName(string $name): ?Group
    {
        return $this->groupRepository->findByName($name);
    }
}
