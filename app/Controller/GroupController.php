<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Service\GroupService;
use App\Service\GroupUserService;

class GroupController {
    private GroupService $groupService;
    private GroupUserService $groupUserService;

    public function __construct(GroupService $groupService, GroupUserService $groupUserService) {
        $this->groupService = $groupService;
        $this->groupUserService = $groupUserService;
    }

    public function createGroup(Request $request, Response $response) : Response {
        try {
            // Get body data
            $data = json_decode($request->getBody()->getContents(), true);

            // Get user id from request attribute
            $userId = $request->getAttribute('user_id');

            // Check if group name is empty
            if (empty($data['group_name'])) {
                return $this->jsonResponse($response, 400, ['error' => 'Group name is required']);
            }

            // Create group and add user to group
            $group = $this->groupService->createGroup($data['group_name'], $userId);

            return $this->jsonResponse($response, 201, [
                'message' => 'Group created successfully',
                'group_id' => $group->getId(),
                'group_name' => $group->getName()
            ]);
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
