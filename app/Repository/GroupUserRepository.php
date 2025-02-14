<?php

namespace App\Repository;

use App\Model\GroupUser;

interface GroupUserRepository {
    public function findById(int $id) : ?GroupUser;
    public function save(GroupUser $userGroup) : GroupUser;
    public function update(GroupUser $userGroup) : GroupUser;
    public function delete(int $id) : void;
    public function findAll() : array;
    public function findByUserId(int $userId) : array;
    public function findByGroupId(int $groupId) : array;
}
