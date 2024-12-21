<?php

namespace MiW\Results\Tests;

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use PHPUnit\Framework\TestCase;

class DeleteResultTest extends TestCase
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

    public function testDeleteResult()
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

        $resultId = $result->getId();

        $createdResult = $this->entityManager->find(Result::class, $resultId);
        $this->assertNotNull($createdResult, "El resultado no fue creado.");

        $this->entityManager->remove($createdResult);
        $this->entityManager->flush();

        $deletedResult = $this->entityManager->find(Result::class, $resultId);
        $this->assertNull($deletedResult, "El resultado no fue eliminado.");
    }
}
