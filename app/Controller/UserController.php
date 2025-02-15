<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Service\UserService;
use App\Model\User;

class UserController {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function createSession(Request $request, Response $response) : Response {
        $data = json_decode($request->getBody()->getContents(), true);

        $redirectUrl = $request->getQueryParams()['redirect'] ?? null;

        if (empty($data['username'])) {
            return $this->jsonResponse($response, 400, ['error' => 'Username is required']);
        }


        $user = $this->userService->createOrGetSession($data['username']);

        $response = $response->withHeader('Authorization', 'Bearer ' . $user->getToken())
            ->withHeader('Content-Type', 'application/json');


        $response = $this->jsonResponse($response, 200,
            [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'token' => $user->getToken(),
                'expires_at' => $user->getTokenExpiry()
            ]
        );


        if ($redirectUrl) {
            $response = $response->withHeader('Redirect-To', $redirectUrl);
        }

        return $response;


    }


    private function jsonResponse(Response $response, int $status, $data) : Response {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
