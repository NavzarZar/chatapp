<?php

namespace App\Repository;

use App\Model\Message;

interface MessageRepository {
    public function findById(int $id) : ?Message;
    public function save(Message $message) : Message;
    public function update(Message $message) : Message;
    public function delete(int $id) : void;
    public function findAll() : array;
    public function findByContent(string $content) : array;
    public function findByGroupId(int $groupId);
}
