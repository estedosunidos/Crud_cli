<?php

namespace MiW\Results\Tests;

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use PHPUnit\Framework\TestCase;

class ListResultsTest extends TestCase
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

    public function testListResults()
    {
        $user = new \MiW\Results\Entity\User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $result1 = new Result(85, $user, new DateTime('2023-12-12 12:00:00'));
        $result2 = new Result(90, $user, new DateTime('2023-12-12 13:00:00'));

        $this->entityManager->persist($result1);
        $this->entityManager->persist($result2);
        $this->entityManager->flush();

        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/list_results.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('85', $output);
        $this->assertStringContainsString('90', $output);
        $this->assertStringContainsString('testuser', $output);
        $this->assertStringContainsString('12:00:00', $output);
        $this->assertStringContainsString('13:00:00', $output);
    }

    public function testListResultsJson()
    {
        $user = new \MiW\Results\Entity\User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $result1 = new Result(85, $user, new DateTime('2023-12-12 12:00:00'));
        $result2 = new Result(90, $user, new DateTime('2023-12-12 13:00:00'));

        $this->entityManager->persist($result1);
        $this->entityManager->persist($result2);
        $this->entityManager->flush();

        $_SERVER['argv'] = ['list_results.php', '--json'];
        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/list_results.php';
        $output = ob_get_clean();

        $jsonOutput = json_decode($output, true);
        $this->assertIsArray($jsonOutput);
        $this->assertCount(2, $jsonOutput);
        $this->assertEquals(85, $jsonOutput[0]['result']);
        $this->assertEquals(90, $jsonOutput[1]['result']);
    }
}
