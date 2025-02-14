<?php

use PHPUnit\Framework\TestCase;
use App\Service\UserServiceImpl;
use App\Repository\UserRepository;
use App\Model\User;


class UserServiceTest extends TestCase
{
    private $userService;
    private $userRepository;

    public function setUp(): void
    {
        // Create mock for user repo
        $this->userRepository = $this->createMock(UserRepository::class);

        // Inject the mock :D
        $this->userService = new UserServiceImpl($this->userRepository);
    }

    public function testCreateOrGetSessionNewUser()
    {
        // Mock findByUsername to return null (user does not exist)
        $this->userRepository->expects($this->once())
            ->method('findByUsername')
            ->with('testuser')
            ->willReturn(null);

        // Mock save to return a user
        $mockUser = new User(1, 'testuser', 'generated-token', '2025-01-01 12:00:00');
        $this->userRepository->expects($this->once())
            ->method('save')
            ->willReturn($mockUser);

        $user = $this->userService->createOrGetSession('testuser');

        $this->assertEquals('testuser', $user->getUsername());
        $this->assertNotEmpty($user->getToken());
        $this->assertNotEmpty($user->getTokenExpiry());
    }

    public function testCreateOrGetSessionExistingUser() {

        // Mock findByUsername to return an existing user
        $existingUser = new User(1, 'testuser', 'existing-token', '2025-01-01 12:00:00');
        $this->userRepository->expects($this->once())
            ->method('findByUsername')
            ->with('testuser')
            ->willReturn($existingUser);

        // Mock updateToken to do nothing (void return)
        $this->userRepository->expects($this->once())
            ->method('updateToken')
            ->with($existingUser->getId(), $this->isType('string'), $this->isType('string'));

        $user = $this->userService->createOrGetSession('testuser');
        $this->assertEquals('testuser', $user->getUsername());
    }

    public function testAuthenticateByTokenValid() {
        // Create a valid expiry so that we do not get null
        $validExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Mock findByToken to return a user
        $mockUser = new User(1, 'testuser', 'valid-token', $validExpiry);
        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with('valid-token')
            ->willReturn($mockUser);

        // Mock updateTokenExpiry (extend expiry)
        $this->userRepository->expects($this->once())
            ->method('updateTokenExpiry');

        $user = $this->userService->authenticateByToken('valid-token');

        $this->assertNotNull($user);
        $this->assertEquals('testuser', $user->getUsername());
    }

    public function testAuthenticateByTokenExpired() {
        // Mock findByToken to return a user with an expired token
        $expiredUser = new User(1, 'testuser', 'expired-token', '2000-01-01 00:00:00');
        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with('expired-token')
            ->willReturn($expiredUser);

        $user = $this->userService->authenticateByToken('expired-token');
        $this->assertNull($user, "Token should be expired and authentication should fail");
    }
}