<?php

namespace App\Service;

use App\Model\GroupUser;
use App\Repository\GroupUserRepository;

class GroupUserServiceImpl implements GroupUserService
{
    private GroupUserRepository $userGroupRepository;

    public function __construct(GroupUserRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }


    public function findById(int $id): ?GroupUser
    {
        return $this->userGroupRepository->findById($id);
    }

    public function save(GroupUser $userGroup): GroupUser
    {
        // Check if tuple exists already
        $existingUserGroup = $this->userGroupRepository->findByUserId($userGroup->getUserId());
        // Array of group ids
        $groupIds = array_map(function($group) {
            return $group->getGroupId();
        }, $existingUserGroup);

        if (in_array($userGroup->getGroupId(), $groupIds)) {
            // Throw exception, user already in group
            throw new \PDOException('User already in group', 409);
        }

        return $this->userGroupRepository->save($userGroup);
    }

    public function update(GroupUser $userGroup): GroupUser
    {
        return $this->userGroupRepository->update($userGroup);
    }

    public function delete(int $id): void
    {
        $this->userGroupRepository->delete($id);
    }

    public function findAll(): array
    {
        return $this->userGroupRepository->findAll();
    }

    public function findByUserId(int $userId): array
    {
        return $this->userGroupRepository->findByUserId($userId);
    }

    public function findByGroupId(int $groupId): array
    {
        return $this->userGroupRepository->findByGroupId($groupId);
    }

    public function getUsersFromGroup(int $groupId, int $userId): array
    {
        // Only return users from group if user in group
        $users = $this->userGroupRepository->getUsersFromGroup($groupId);
        $userIds = array_map(function($user) {
            return $user->getId();
        }, $users);

        if (in_array($userId, $userIds)) {
            return $users;
        } else {
            // Throw exception, user not in group
            throw new \PDOException('User not in group', 404);
        }
    }
}
