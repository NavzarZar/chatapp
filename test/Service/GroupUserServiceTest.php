<?php

use App\Service\GroupUserServiceImpl;
use App\Model\GroupUser;
use App\Repository\GroupUserRepository;
use PHPUnit\Framework\TestCase;

class GroupUserServiceTest extends TestCase {
    private $groupUserService;
    private GroupUserRepository $groupUserRepository;

    public function setUp() : void
    {
        $this->groupUserRepository = $this->createMock(GroupUserRepository::class);
        $this->groupUserService = new GroupUserServiceImpl($this->groupUserRepository);
    }

    public function testFindById() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($groupUser);
        $this->assertEquals($groupUser, $this->groupUserService->findById(1));
    }

    public function testSave() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('save')
            ->with($groupUser)
            ->willReturn($groupUser);
        $this->assertEquals($groupUser, $this->groupUserService->save($groupUser));
    }

    public function testUpdate() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('update')
            ->with($groupUser)
            ->willReturn($groupUser);
        $this->assertEquals($groupUser, $this->groupUserService->update($groupUser));
    }

    public function testDelete() {
        $this->groupUserRepository->expects($this->once())
            ->method('delete')
            ->with(1);
        $this->groupUserService->delete(1);
    }

    public function testFindAll() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$groupUser]);
        $this->assertEquals([$groupUser], $this->groupUserService->findAll());
    }

    public function testFindByUserId() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('findByUserId')
            ->with(1)
            ->willReturn([$groupUser]);
        $this->assertEquals([$groupUser], $this->groupUserService->findByUserId(1));
    }

    public function testFindByGroupId() {
        $groupUser = new GroupUser(1, 1, 1);
        $this->groupUserRepository->expects($this->once())
            ->method('findByGroupId')
            ->with(1)
            ->willReturn([$groupUser]);
        $this->assertEquals([$groupUser], $this->groupUserService->findByGroupId(1));
    }
}