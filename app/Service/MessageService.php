<?php

namespace App\Service;

use App\Model\Message;

interface MessageService {
    public function getMessageById(int $id);
    public function sendMessage(int $userId, int $groupId, string $content) : Message;
    public function updateMessage(Message $message) : Message;
    public function deleteMessage(int $userId, int $messageId) : void;
    public function getAllMessages() : array;
    public function getMessagesByContent(string $content) : array;
    public function getMessagesFromGroup(int $userId, int $groupId) : array;
}
