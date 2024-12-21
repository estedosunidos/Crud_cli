<?php

use PHPUnit\Framework\TestCase;
use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

class UpdateResultTets extends TestCase
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

    public function testUpdateResult()
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

        $argv = [
            'update_result.php', 
            (string) $result->getId(), 
            '90', 
            '2023-12-13 12:00:00'
        ];
        $_SERVER['argv'] = $argv;
        $_SERVER['argc'] = count($argv);

        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/update_result.php';  
        $output = ob_get_clean();

        $this->assertStringContainsString("Resultado con ID {$result->getId()} actualizado.", $output);

        $updatedResult = $this->entityManager->find(Result::class, $result->getId());
        $this->assertEquals(90, $updatedResult->getResult());
        $this->assertEquals('2023-12-13 12:00:00', $updatedResult->getTimestamp()->format('Y-m-d H:i:s'));
    }

    public function testUpdateResultNotFound()
    {
        $argv = [
            'update_result.php', 
            '9999', 
            '90', 
            '2023-12-13 12:00:00' 
        ];
        $_SERVER['argv'] = $argv;
        $_SERVER['argc'] = count($argv);

        ob_start();
        include dirname(__DIR__, 2) . '/src/scripts/update_result.php';  
        $output = ob_get_clean();

        $this->assertStringContainsString("Resultado con ID 9999 no encontrado.", $output);
    }
}
