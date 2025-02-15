<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Service\UserService;
use Psr\Http\Server\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function process(Request $request, RequestHandler $handler) : Response {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return $this->unauthorizedResponse($request);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $user = $this->userService->authenticateByToken($token);

        if (!$user) {
            return $this->unauthorizedResponse($request);
        }

        // Attach user_id to request
        $request = $request->withAttribute('user_id', $user->getId());

        return $handler->handle($request);
    }

    private function unauthorizedResponse() : Response {
        $response = new \Slim\Psr7\Response();

        $response->getBody()->write(json_encode(['error' => 'Unauthorized. Please provide a valid token in the Authorization header.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

}
