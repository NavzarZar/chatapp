<?php

use App\Repository\GroupUserRepository;
use App\Model\GroupUser;
use PHPUnit\Framework\TestCase;

use Database\Migrations\PopulateDatabase;
use Database\Migrations\DeleteAllTables;

class GroupUserRepositoryTest extends TestCase
{
    private GroupUserRepository $userGroupRepository;

    private $container;

    protected function setUp(): void
    {
        // Load the DI container
        $this->container = require __DIR__ . '/../../config/dependencies.php';

        // Get GroupUserRepository from the container
        $this->userGroupRepository = $this->container->get(GroupUserRepository::class);

        // Populate the database from container
        $populator = new PopulateDatabase('/data/test.db');
        $populator->up();
    }

    protected function tearDown() : void
    {
        // Delete all tables from the database
        $deleter = new DeleteAllTables('/data/test.db');
        $deleter->up();
    }

    public function testFindById(): void
    {
        $userGroup = $this->userGroupRepository->findById(1);
        $this->assertNotNull($userGroup);
    }

    public function testSave(): void
    {
        $userGroup = new GroupUser(null, 4, 4);
        $savedUserGroup = $this->userGroupRepository->save($userGroup);
        $this->assertNotNull($savedUserGroup);
    }

    public function testUpdate(): void
    {
        $userGroup = $this->userGroupRepository->findById(1);
        $userGroup->setUserId(5);
        $updatedUserGroup = $this->userGroupRepository->update($userGroup);
        $this->assertEquals($userGroup->getUserId(), $updatedUserGroup->getUserId());
    }

    public function testDelete(): void
    {
        $userGroup = new GroupUser(null, 3, 3);
        $savedUserGroup = $this->userGroupRepository->save($userGroup);
        $this->assertNotNull($savedUserGroup);

        $this->userGroupRepository->delete($savedUserGroup->getId());
        $deletedUserGroup = $this->userGroupRepository->findById($savedUserGroup->getId());
        $this->assertNull($deletedUserGroup);
    }

    public function testFindAll(): void
    {
        $userGroups = $this->userGroupRepository->findAll();
        $this->assertIsArray($userGroups);
    }

    public function testFindByUserId(): void
    {
        $userGroups = $this->userGroupRepository->findByUserId(1);
        $this->assertIsArray($userGroups);
    }

    public function testFindByGroupId(): void
    {
        $userGroups = $this->userGroupRepository->findByGroupId(1);
        $this->assertIsArray($userGroups);
    }
}
