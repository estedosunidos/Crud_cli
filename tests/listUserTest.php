<?php

namespace MiW\Tests\Results;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

class ListUsersTest extends TestCase
{
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $this->userRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);

        $this->entityManager->method('getRepository')->willReturn($this->userRepository);
    }

    public function testListUsers(): void
    {
        $user1 = $this->createMock(User::class);
        $user1->method('getId')->willReturn(1);
        $user1->method('getUsername')->willReturn('user1');
        $user1->method('getEmail')->willReturn('user1@example.com');
        $user1->method('isEnabled')->willReturn(true);

        $user2 = $this->createMock(User::class);
        $user2->method('getId')->willReturn(2);
        $user2->method('getUsername')->willReturn('user2');
        $user2->method('getEmail')->willReturn('user2@example.com');
        $user2->method('isEnabled')->willReturn(false);

        $this->userRepository->method('findAll')->willReturn([$user1, $user2]);

        ob_start(); 
        require dirname(__DIR__, 2) . '/src/scripts/list_users.php';
        $output = ob_get_clean(); 

        $this->assertStringContainsString("ID: 1 | Username: user1 | Email: user1@example.com | Enabled: 1", $output);
        $this->assertStringContainsString("ID: 2 | Username: user2 | Email: user2@example.com | Enabled: 0", $output);
    }
}
