<?php

namespace App\Repository;

use App\Model\User;

interface UserRepository
{
    public function findById(int $id) : ?User;
    public function save(User $user) : User;
}