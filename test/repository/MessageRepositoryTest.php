<?php

use PHPUnit\Framework\TestCase;
use App\Repository\MessageRepository;
use App\Model\Message;

class MessageRepositoryTest extends TestCase
{
    private MessageRepository $messageRepository;

    private $container;


    protected function setUp(): void
    {
        // Load the DI container
        $this->container = require __DIR__ . '/../../config/dependencies.php';

        // Get MessageRepository from the container
        $this->messageRepository = $this->container->get(MessageRepository::class);
    }

    public function testFindById(): void
    {
        $message = $this->messageRepository->findById(1);
        $this->assertNotNull($message);
    }

    public function testSave(): void
    {
        $message = new Message(null, 1, 1, "Hello, World!", "2021-08-01 00:00:00");
        $savedMessage = $this->messageRepository->save($message);
        $this->assertNotNull($savedMessage);
    }

    public function testUpdate(): void
    {
        $message = $this->messageRepository->findById(1);
        $message->setContent("Hello, World! Updated");
        $updatedMessage = $this->messageRepository->update($message);
        $this->assertEquals($message->getContent(), $updatedMessage->getContent());
    }

    public function testDelete(): void
    {
        $message = new Message(null, 1, 1, "Hello, World!2", "2021-08-01 00:00:00");
        $savedMessage = $this->messageRepository->save($message);
        $this->assertNotNull($savedMessage);

        $this->messageRepository->delete($savedMessage->getId());
        $deletedMessage = $this->messageRepository->findById($savedMessage->getId());
        $this->assertNull($deletedMessage);
    }

    public function testFindAll(): void
    {
        $messages = $this->messageRepository->findAll();
        $this->assertIsArray($messages);
    }

    public function testFindByContent(): void
    {
        $messages = $this->messageRepository->findByContent("Hello, World!");
        $this->assertIsArray($messages);
    }
}


