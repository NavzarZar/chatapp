<?php

namespace App\Controller;

use App\Service\MessageService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageController {
    private MessageService $messageService;

    public function __construct(MessageService $messageService) {
        $this->messageService = $messageService;
    }

    public function sendMessage(Request $request, Response $response) : Response {
        try {
            // Get body data
            $data = json_decode($request->getBody()->getContents(), true);

            // Get user id from request attribute
            $userId = $request->getAttribute('user_id');
            $groupId = $request->getAttribute('group_id');

            // Check if group id is empty
            if (empty($groupId)) {
                return $this->jsonResponse($response, 400, ['error' => 'Group ID is required']);
            }


            // Check if message is empty
            if (empty($data['content'])) {
                return $this->jsonResponse($response, 400, ['error' => 'Message content is required']);
            }

            // Send message
            $message = $this->messageService->sendMessage($userId, $groupId, $data['content']);

            return $this->jsonResponse($response, 201, [
                'message' => 'Message sent successfully',
                'message_id' => $message->getId(),
                'group_id' => $message->getGroupId(),
                'user_id' => $message->getUserId(),
                'content' => $message->getContent(),
                'created_at' => $message->getTimestamp()
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, $e->getCode(), ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, ['error' => 'Internal server error']);
        }
    }


    // Get group messages
    public function getGroupMessages(Request $request, Response $response) : Response {
        try {
            // Get group id from request attribute
            $groupId = $request->getAttribute('group_id');

            // Get user id from request attribute
            $userId = $request->getAttribute('user_id');

            // Check if group id is empty
            if (empty($groupId)) {
                return $this->jsonResponse($response, 400, ['error' => 'Group ID is required']);
            }

            // Get messages
            $messages = $this->messageService->getMessagesFromGroup($userId, $groupId);

            // Format messages
            $formattedMessages = array_map(fn($message) => [
                'id' => $message->getId(),
                'group_id' => $message->getGroupId(),
                'user_id' => $message->getUserId(),
                'content' => $message->getContent(),
                'created_at' => $message->getTimestamp()
            ], $messages);

            return $this->jsonResponse($response, 200, $formattedMessages);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, $e->getCode(), ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, ['error' => 'Internal server error']);
        }
    }

    private function jsonResponse(Response $response, int $status, $data) : Response {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
