<?php

namespace App\Repository;

use App\Model\Message;

use PDO;

class MessageRepositoryImpl implements MessageRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Message
    {
        $stmt = $this->pdo->prepare('SELECT * FROM message WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Message($data['id'], $data['user_id'], $data['group_id'], $data['content'], $data['timestamp']) : null;
    }

    public function save(Message $message): Message
    {
        $stmt = $this->pdo->prepare('INSERT INTO message (user_id, group_id, content, timestamp) VALUES (:user_id, :group_id, :content, :timestamp)');
        $stmt->execute([
            'user_id' => $message->getUserId(),
            'group_id' => $message->getGroupId(),
            'content' => $message->getContent(),
            'timestamp' => $message->getTimestamp()
        ]);

        $newMessageId = (int) $this->pdo->lastInsertId();

        return new Message($newMessageId, $message->getUserId(), $message->getGroupId(), $message->getContent(), $message->getTimestamp());

    }

    public function update(Message $message): Message
    {
        $stmt = $this->pdo->prepare('UPDATE message SET content = :content, timestamp = :timestamp WHERE id = :id');
        $stmt->execute([
            'content' => $message->getContent(),
            'timestamp' => $message->getTimestamp(),
            'id' => $message->getId()
        ]);

        return $message;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM message WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM message');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];
        foreach ($data as $message) {
            $messages[] = new Message($message['id'], $message['user_id'], $message['group_id'], $message['content'], $message['timestamp']);
        }

        return $messages;
    }

    public function findByContent(string $content): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM message WHERE content = :content');
        $stmt->execute(['content' => $content]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];
        foreach ($data as $message) {
            $messages[] = new Message($message['id'], $message['user_id'], $message['group_id'], $message['content'], $message['timestamp']);
        }

        return $messages;
    }

    public function findByGroupId(int $groupId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM message WHERE group_id = :group_id');
        $stmt->execute(['group_id' => $groupId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];
        foreach ($data as $message) {
            $messages[] = new Message($message['id'], $message['user_id'], $message['group_id'], $message['content'], $message['timestamp']);
        }

        return $messages;
    }
}
