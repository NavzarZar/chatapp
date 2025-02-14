<?php

use PHPUnit\Framework\TestCase;
use App\Repository\GroupRepository;
use App\Model\Group;

class GroupRepositoryTest extends TestCase {

    private GroupRepository $groupRepository;
    private $container;

    protected function setUp() : void
    {
        // Load DI container
        $this->container = require __DIR__ . '/../../config/dependencies.php';

        // Get GroupRepository from the container
        $this->groupRepository = $this->container->get(GroupRepository::class);
    }

    public function testFindById()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test'));

        // Get the group id
        $id = $group->getId();

        // Find the group
        $retrievedGroup = $this->groupRepository->findById($id);

        // Check if the group is found
        $this->assertEquals($group->getName(), $retrievedGroup->getName(), "Group should be found");
    }

    public function testSaveGroup()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test1'));

        // Get the group id
        $id = $group->getId();

        // Find the group
        $retrievedGroup = $this->groupRepository->findById($id);

        // Check if the group is found
        $this->assertEquals($group->getName(), $retrievedGroup->getName(), "Group should be found");
    }

    public function testUpdateGroup()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test2'));

        // Get the group id
        $id = $group->getId();

        // Update the group
        $group->setName('test3');
        $this->groupRepository->update($group);

        // Find the group
        $retrievedGroup = $this->groupRepository->findById($id);

        // Check if the group is updated
        $this->assertEquals($group->getName(), $retrievedGroup->getName(), "Group should be updated");
    }

    public function testDeleteGroup()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test4'));

        // Get the group id
        $id = $group->getId();

        // Delete the group
        $this->groupRepository->delete($id);

        // Find the group
        $retrievedGroup = $this->groupRepository->findById($id);

        // Check if the group is deleted
        $this->assertNull($retrievedGroup, "Group should be deleted");
    }

    public function testFindAll()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test5'));

        // Find all groups
        $groups = $this->groupRepository->findAll();

        // Check if the group is found
        $this->assertNotEmpty($groups, "Groups should be found");
    }

    public function testFindByName()
    {
        // Insert a group into the database
        $group = $this->groupRepository->save(new Group(null, 'test6'));

        // Find the group
        $retrievedGroup = $this->groupRepository->findByName('test6');

        // Check if the group is found
        $this->assertEquals($group->getName(), $retrievedGroup->getName(), "Group should be found");
    }
}
