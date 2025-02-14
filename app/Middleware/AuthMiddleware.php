<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use App\Service\UserService;

class AuthMiddleware implements MiddlewareInterface {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function process(Request $request, RequestHandler $handler) : Response {
        $token = $request->getHeader('Authorization')[0] ?? null;

        $response = new \Slim\Psr7\Response();

        if (!$token) {
            return $this->jsonResponse($response, ['error' => 'Unauthorized']);
        }

        $user = $this->userService->authenticateByToken($token);

        if (!$user) {
            return $this->jsonResponse($response, ['error' => 'Invalid or expired token']);
        }

        $request = $request->withAttribute('user_id', $user->getId());

        return $handler->handle($request);
    }

    private function jsonResponse(Response $response, $data) : Response {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
