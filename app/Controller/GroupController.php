<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Service\GroupService;
use App\Service\GroupUserService;

use App\Model\GroupUser;

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

    public function listGroups(Request $request, Response $response) : Response {
        try {
            // Get all groups
            $groups = $this->groupService->getAllGroups();

            // Format groups
            $formattedGroups = array_map(fn($group) => [
                'id' => $group->getId(),
                'name' => $group->getName()
            ], $groups);

            return $this->jsonResponse($response, 200, $formattedGroups);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, $e->getCode(), ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, ['error' => 'Internal server error']);
        }
    }

    public function getUsersFromGroup(Request $request, Response $response) : Response {
        try {
            // Get group id from request attribute
            $groupId = $request->getAttribute('group_id');

            $userId = $request->getAttribute('user_id');

            // Get users from group
            $users = $this->groupUserService->getUsersFromGroup($groupId, $userId);

            $formattedUsers = array_map(fn($user) => [
                'id' => $user->getId(),
                'username' => $user->getUsername()
            ], $users);

            return $this->jsonResponse($response, 200, $formattedUsers);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, $e->getCode(), ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            echo $e;
            return $this->jsonResponse($response, 500, ['error' => 'Internal server error']);
        }
    }

    public function joinGroup(Request $request, Response $response) : Response {
        try {
            // Get group id from request attribute
            $groupId = $request->getAttribute('group_id');

            // Get user id from request attribute
            $userId = $request->getAttribute('user_id');

            $this->groupUserService->save(new GroupUser(null, $userId, $groupId));

            return $this->jsonResponse($response, 201, ['message' => 'User joined group successfully']);
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
