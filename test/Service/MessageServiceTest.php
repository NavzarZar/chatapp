<?php


use PHPUnit\Framework\TestCase;
use App\Service\MessageServiceImpl;
use App\Model\Message;
use App\Repository\MessageRepository;
use App\Repository\GroupUserRepository;

class MessageServiceTest extends TestCase {
    private MessageServiceImpl $messageService;
    private MessageRepository $messageRepository;
    private GroupUserRepository $groupUserRepository;

    public function setUp(): void {
        $this->messageRepository = $this->createMock(MessageRepository::class);
        $this->groupUserRepository = $this->createMock(GroupUserRepository::class);
        $this->messageService = new MessageServiceImpl($this->messageRepository, $this->groupUserRepository);
    }

    public function testGetMessageById() {
        $message = new Message(1, 1, 1, 'test', '2021-08-01 00:00:00');
        $this->messageRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($message);

        $this->assertEquals($message, $this->messageService->getMessageById(1));
    }

    public function testCreateMessage()
    {
        $mockTimestamp = '2025-01-01 12:00:00';

        // Mock MessageRepository to return a predefined timestamp
        $this->messageRepository->expects($this->once())
            ->method('save')
            ->willReturn(new Message(1, 1, 1, 'test', $mockTimestamp));

        // Call service method
        $savedMessage = $this->messageService->sendMessage(1, 1, 'test');

        // Assert expected values
        $this->assertEquals('2025-01-01 12:00:00', $savedMessage->getTimestamp());

    }


    public function testDeleteMessage() {

        $mockMessage = new Message(1, 1, 1, 'test', '2025-01-01 00:00:00');

        $this->messageRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->messageRepository->expects($this->exactly(2))
            ->method('findById')
            ->with(1)
            ->willReturnOnConsecutiveCalls($mockMessage, null);

        $this->messageService->deleteMessage(1, 1);

        $this->assertNull($this->messageService->getMessageById(1));
    }

    public function testGetAllMessages() {
        $messages = [new Message(1, 1, 1, 'test1', '2021-08-01 00:00:00'), new Message(2, 1, 1, 'test2', '2021-08-01 00:00:00')];
        $this->messageRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($messages);

        $this->assertEquals($messages, $this->messageService->getAllMessages());
    }

    public function testGetMessagesByContent() {
        $messages = [new Message(1, 1, 1, 'test', '2021-08-01 00:00:00'), new Message(2, 1, 2, 'test', '2021-08-01 00:00:00')];
        $this->messageRepository->expects($this->once())
            ->method('findByContent')
            ->with('test')
            ->willReturn($messages);

        $this->assertEquals($messages, $this->messageService->getMessagesByContent('test'));
    }

}

