<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Service\UserService;

class UserController {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function createSession(Request $request, Response $response) : Response {
        $data = json_decode($request->getBody()->getContents(), true);

        if (empty($data['username'])) {
            return $this->jsonResponse($response, 400, ['error' => 'Username is required']);
        }

        $user = $this->userService->createOrGetSession($data['username']);

        return $this->jsonResponse($response, 200,
            [
                'username' => $user->getUsername(),
                'token' => $user->getToken(),
                'expires_at' => $user->getTokenExpiry()
            ]
        );
    }

    private function jsonResponse(Response $response, int $status, $data) : Response {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
