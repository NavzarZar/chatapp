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
}
