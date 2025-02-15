<?php

namespace App\Service;

use App\Model\Message;
use App\Repository\MessageRepository;
use App\Repository\GroupUserRepository;
use App\Model\GroupUser;

class MessageServiceImpl implements MessageService {
    private MessageRepository $messageRepository;
    private GroupUserRepository $groupUserRepository;

    public function __construct(MessageRepository $messageRepository, GroupUserRepository $groupUserRepository) {
        $this->messageRepository = $messageRepository;
        $this->groupUserRepository = $groupUserRepository;
    }

    public function getMessageById(int $id) : ?Message {
        return $this->messageRepository->findById($id);
    }

    public function sendMessage(int $userId, int $groupId, string $content) : Message {
        // Time when it was sent
        $timestamp = date('Y-m-d H:i:s');

        $message = new Message(null, $userId, $groupId, $content, $timestamp);
        return $this->messageRepository->save($message);
    }

    public function updateMessage(Message $message) : Message {
        return $this->messageRepository->update($message);
    }

    public function deleteMessage(int $userId, int $messageId) : void {
        // Only delete message if user sent it
        $message = $this->messageRepository->findById($messageId);
        if ($message->getUserId() !== $userId) {
            // Throw exception along with correct code
            throw new \PDOException('User did not send message', 401);
        }

        $this->messageRepository->delete($messageId);
    }

    public function getAllMessages() : array {
        return $this->messageRepository->findAll();
    }

    public function getMessagesByContent(string $content) : array {
        return $this->messageRepository->findByContent($content);
    }

    public function getMessagesFromGroup(int $userId, int $groupId): array
    {
        // Only return if user id is in group
        $userGroups = $this->groupUserRepository->findByUserId($userId);
        $groupIds = array_map(function($group) {
            return $group->getGroupId();
        }, $userGroups);

        if (!in_array($groupId, $groupIds)) {
            // Throw exception along with correct code
            throw new \PDOException('User is not in group', 401);
        }


        return $this->messageRepository->findByGroupId($groupId);
    }
}
