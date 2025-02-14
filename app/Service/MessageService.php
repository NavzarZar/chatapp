<?php

namespace App\Service;

use App\Model\Message;

interface MessageService {
    public function getMessageById(int $id);
    public function createMessage(int $userId, int $groupId, string $content) : Message;
    public function updateMessage(Message $message) : Message;
    public function deleteMessage(int $id) : void;
    public function getAllMessages() : array;
    public function getMessagesByContent(string $content) : array;
}
