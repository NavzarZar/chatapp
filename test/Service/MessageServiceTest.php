<?php


use PHPUnit\Framework\TestCase;
use App\Service\MessageServiceImpl;
use App\Model\Message;
use App\Repository\MessageRepository;

class MessageServiceTest extends TestCase {
    private MessageServiceImpl $messageService;
    private MessageRepository $messageRepository;

    public function setUp(): void {
        $this->messageRepository = $this->createMock(MessageRepository::class);
        $this->messageService = new MessageServiceImpl($this->messageRepository);
    }

    public function testGetMessageById() {
        $message = new Message(1, 1, 1, 'test', '2021-08-01 00:00:00');
        $this->messageRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($message);

        $this->assertEquals($message, $this->messageService->getMessageById(1));
    }

    public function testCreateMessage() {
        $message = new Message(1, 1, 1, 'test', '2021-08-01 00:00:00');
        $this->messageRepository->expects($this->once())
            ->method('save')
            ->with(new Message(null, 1, 1, 'test', '2021-08-01 00:00:00'))
            ->willReturn($message);

        $this->assertEquals($message, $this->messageService->createMessage(1, 1, 'test', '2021-08-01 00:00:00'));
    }

    public function testUpdateMessage() {
        $message = new Message(1, 1, 1, 'test', '2021-08-01 00:00:00');
        $this->messageRepository->expects($this->once())
            ->method('update')
            ->with($message)
            ->willReturn($message);

        $this->assertEquals($message, $this->messageService->updateMessage($message));
    }

    public function testDeleteMessage() {
        $this->messageRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->messageService->deleteMessage(1);
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

