<?php

use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use App\Model\User;

use Database\Migrations\PopulateDatabase;
use Database\Migrations\DeleteAllTables;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private $container;

    protected function setUp(): void
    {
        // Load the DI container
        $this->container = require __DIR__ . '/../../config/dependencies.php';

        // Get UserRepository from the container
        $this->userRepository = $this->container->get(UserRepository::class);

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


    public function testPDOAutowired() {

        // Get pdo from UserRepository using reflection
        $reflection = new \ReflectionClass($this->userRepository);
        $property = $reflection->getProperty('pdo');
        $property->setAccessible(true);
        $pdoFromRepository = $property->getValue($this->userRepository);

        // Get the actual expected path
        $config = require __DIR__ . '/../../config/database_config.php';
        $expectedPath = realpath($config['database_path']);

        // Get the actual path from the database
        $stmt = $pdoFromRepository->query("PRAGMA database_list");
        $result = $stmt->fetch();
        $actualDatabasePath = $result['file'] ?? '';

        // Check if the paths are equal
        $this->assertStringContainsString($expectedPath, $actualDatabasePath, "PDO should be connected to the test database");

        // Check if the pdo is an instance of PDO
        $this->assertInstanceOf(PDO::class, $pdoFromRepository, "PDO should be an instance of PDO");
    }

    public function testFindById()
    {
        // Insert a user into the database
        $user = $this->userRepository->save(new User(null, 'test', 'test', '2100-01-01 00:00:00'));

        // Get his id
        $id = $user->getId();

        // Find the user
        $retrievedUser = $this->userRepository->findById($id);

        // Check if the user is found
        $this->assertEquals($user->getUsername(), $retrievedUser->getUsername(), "User should be found");
    }

    public function testSaveUser()
    {
        // Create a user
        $user = new User(null, 'test2', 'test2', '2100-01-01 00:00:00');

        // Save the user
        $savedUser = $this->userRepository->save($user);

        // Retrieve the user
        $retrievedUser = $this->userRepository->findById($savedUser->getId());

        // Saved user and created user should be the same
        $this->assertEquals($savedUser->getUsername(), $user->getUsername(), "User should be saved");

        // Check if users are the same
        $this->assertEquals($savedUser->getUsername(), $retrievedUser->getUsername(), "User should be saved");

    }
}
