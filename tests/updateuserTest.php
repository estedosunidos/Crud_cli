<?php

namespace MiW\Tests\Results;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

class UpdateUserTest extends TestCase
{
    private $entityManager;
    private $userRepository;
    private $user;

    protected function setUp(): void
    {
        // Mock the EntityManager and UserRepository
        $this->entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $this->userRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);

        // Mock the EntityManager to return the UserRepository when called
        $this->entityManager->method('getRepository')->willReturn($this->userRepository);

        // Create a mock User object
        $this->user = $this->createMock(User::class);

        // Mock the repository to return the mock User object when calling find()
        $this->userRepository->method('find')->willReturn($this->user);
    }

    public function testUpdateUser(): void
    {
        // Arrange: Set up mock User methods
        $this->user->expects($this->once())->method('setUsername')->with('updated_username')->willReturnSelf();
        $this->user->expects($this->once())->method('setEmail')->with('updated_email@example.com')->willReturnSelf();
        $this->user->expects($this->once())->method('setPassword')->with($this->anything())->willReturnSelf();
        $this->user->expects($this->once())->method('setEnabled')->with(true)->willReturnSelf();

        // Capture the output
        ob_start();
        // Simulate command line arguments
        $argc = 6;
        $argv = ['update_user.php', '1', 'updated_username', 'updated_email@example.com', 'new_password', '1'];
        require dirname(__DIR__, 2) . '/src/scripts/update_user.php'; // Run the script
        $output = ob_get_clean();

        // Assert: Check if the output contains the correct success message
        $this->assertStringContainsString('Usuario con ID 1 actualizado.', $output);

        // Assert: Ensure the entity manager's flush method is called to persist changes
        $this->entityManager->expects($this->once())->method('flush');
    }

    public function testUserNotFound(): void
    {
        // Mock the repository to return null for find() (simulate user not found)
        $this->userRepository->method('find')->willReturn(null);

        // Capture the output
        ob_start();
        // Simulate command line arguments
        $argc = 6;
        $argv = ['update_user.php', '999', 'non_existent_user', 'non_existent_email@example.com', 'password', '0'];
        require dirname(__DIR__, 2) . '/src/scripts/update_user.php'; // Run the script
        $output = ob_get_clean();

        // Assert: Check if the output contains the correct error message
        $this->assertStringContainsString('Usuario con ID 999 no encontrado.', $output);
    }

    public function testInvalidArguments(): void
    {
        // Capture the output when not enough arguments are provided
        ob_start();
        // Simulate command line arguments
        $argc = 1; // Less than required arguments
        $argv = ['update_user.php'];
        require dirname(__DIR__, 2) . '/src/scripts/update_user.php'; // Run the script
        $output = ob_get_clean();

        // Assert: Check if the output contains the correct usage message
        $this->assertStringContainsString('Usage: php update_user.php <user_id> <username> <email> <password> <enabled>', $output);
    }
}
