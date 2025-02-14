<?php

namespace App\Service;

use App\Model\GroupUser;

interface GroupUserService {
    public function findById(int $id) : ?GroupUser;
    public function save(GroupUser $userGroup) : GroupUser;
    public function update(GroupUser $userGroup) : GroupUser;
    public function delete(int $id) : void;
    public function findAll() : array;
    public function findByUserId(int $userId) : array;
    public function findByGroupId(int $groupId) : array;
    public function getUsersFromGroup(int $groupId, int $userId) : array;
}
