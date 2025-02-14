<?php

use PHPUnit\Framework\TestCase;
use App\Service\GroupService;
use App\Service\GroupServiceImpl;
use App\Model\Group;
use App\Repository\GroupRepository;
use App\Repository\GroupUserRepository;
use App\Model\GroupUser;


class GroupServiceTest extends TestCase {

    private GroupService $groupService;
    private GroupRepository $groupRepository;
    private GroupUserRepository $groupUserRepository;

    public function setUp() : void
    {
        $this->groupRepository = $this->createMock(GroupRepository::class);
        $this->groupUserRepository = $this->createMock(GroupUserRepository::class);

        $this->groupService = new GroupServiceImpl($this->groupRepository, $this->groupUserRepository);
    }

    public function testGetGroupById()
    {
        $group = new Group(1, 'test');
        $this->groupRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($group);

        $this->assertEquals($group, $this->groupService->getGroupById(1));
    }

    public function testCreateGroup()
    {
        $group = new Group(1, 'test');
        $this->groupRepository->expects($this->once())
            ->method('save')
            ->with(new Group(null, 'test'))
            ->willReturn($group);

        $this->groupRepository->expects($this->once())
            ->method('findByName')
            ->with('test')
            ->willReturn($group);

        $this->groupUserRepository->expects($this->once())
            ->method('save')
            ->with(new GroupUser(null, 1, 1));

        $this->assertEquals($group, $this->groupService->createGroup('test', 1));
    }

    public function testUpdateGroup()
    {
        $group = new Group(1, 'test');
        $this->groupRepository->expects($this->once())
            ->method('update')
            ->with($group)
            ->willReturn($group);

        $this->assertEquals($group, $this->groupService->updateGroup($group));
    }

    public function testDeleteGroup()
    {
        $this->groupRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->groupService->deleteGroup(1);
    }

    public function testGetAllGroups()
    {
        $groups = [new Group(1, 'test1'), new Group(2, 'test2')];
        $this->groupRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($groups);

        $this->assertEquals($groups, $this->groupService->getAllGroups());
    }

    public function testGetGroupByName()
    {
        $group = new Group(1, 'test');
        $this->groupRepository->expects($this->once())
            ->method('findByName')
            ->with('test')
            ->willReturn($group);

        $this->assertEquals($group, $this->groupService->getGroupByName('test'));
    }
}
