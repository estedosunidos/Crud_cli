<?php

namespace MiW\Results\Tests;

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use PHPUnit\Framework\TestCase;

class ReadResultTest extends TestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = DoctrineConnector::getEntityManager();
    }

    protected function tearDown(): void
    {
        $this->entityManager->clear();
    }

    public function testReadResult()
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $result = new Result(85, $user, new \DateTime('2023-12-12 12:00:00'));

        $this->entityManager->persist($result);
        $this->entityManager->flush();

        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/read_result.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Resultado encontrado: ID = ' . $result->getId(), $output);
        $this->assertStringContainsString('Result = ' . $result->getResult(), $output);
        $this->assertStringContainsString('User = ' . $user->getUsername(), $output);
    }

    public function testReadResultNotFound()
    {
        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/read_result.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Resultado con ID 9999 no encontrado.', $output);
    }
}
