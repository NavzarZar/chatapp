<?php

namespace App\Service;

use App\Model\Group;
use App\Repository\GroupRepository;


class GroupServiceImpl implements GroupService {

    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getGroupById(int $id)
    {
        return $this->groupRepository->findById($id);
    }

    public function createGroup(string $name): Group
    {
        return $this->groupRepository->save(new Group(null, $name));
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
