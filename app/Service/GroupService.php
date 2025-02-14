<?php

namespace App\Service;

use App\Model\Group;

interface GroupService {
    public function getGroupById(int $id);
    public function createGroup(string $name, int $userId) : Group;
    public function updateGroup(Group $group) : Group;
    public function deleteGroup(int $id) : void;
    public function getAllGroups() : array;
    public function getGroupByName(string $name) : ?Group;
}
