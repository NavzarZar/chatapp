<?php

namespace App\Service;

use App\Model\Message;
use App\Repository\MessageRepository;

class MessageServiceImpl implements MessageService {
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository) {
        $this->messageRepository = $messageRepository;
    }

    public function getMessageById(int $id) {
        return $this->messageRepository->findById($id);
    }

    public function createMessage(int $userId, int $groupId, string $content) : Message {
        $message = new Message(null, $userId, $groupId, $content, '2021-08-01 00:00:00');
        return $this->messageRepository->save($message);
    }

    public function updateMessage(Message $message) : Message {
        return $this->messageRepository->update($message);
    }

    public function deleteMessage(int $id) : void {
        $this->messageRepository->delete($id);
    }

    public function getAllMessages() : array {
        return $this->messageRepository->findAll();
    }

    public function getMessagesByContent(string $content) : array {
        return $this->messageRepository->findByContent($content);
    }
}
