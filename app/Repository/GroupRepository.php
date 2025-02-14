<?php

namespace App\Repository;

use App\Model\Group;

interface GroupRepository
{
    public function findById(int $id) : ?Group;
    public function save(Group $group) : Group;
    public function update(Group $group) : Group;
    public function delete(int $id) : void;
    public function findAll() : array;
    public function findByName(string $name) : ?Group;
}
