<?php

use PHPUnit\Framework\TestCase;
use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use Doctrine\ORM\EntityManagerInterface;

class CreateResultTest extends TestCase
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

    public function testCreateResult()
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userId = $user->getId();

        $newResult = 95; 
        $timestamp = new DateTime('now');

        $result = new Result($newResult, $user, $timestamp);

        $this->entityManager->persist($result);
        $this->entityManager->flush();

        $createdResult = $this->entityManager->find(Result::class, $result->getId());

        $this->assertNotNull($createdResult, "El resultado no fue creado.");
        $this->assertEquals($newResult, $createdResult->getResult(), "El resultado no coincide.");
        $this->assertEquals($userId, $createdResult->getUser()->getId(), "El usuario asociado no coincide.");
        $this->assertEquals($timestamp->format('Y-m-d H:i:s'), $createdResult->getTimestamp()->format('Y-m-d H:i:s'), "El timestamp no coincide.");
    }
}
