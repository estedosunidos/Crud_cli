<?php

use PHPUnit\Framework\TestCase;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

class DeleteUserTest extends TestCase
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

    public function testDeleteUser()
    {
        $user = new User();
        $user->setUsername('user_to_delete');
        $user->setEmail('delete_user@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userId = $user->getId();

        $savedUser = $this->entityManager->find(User::class, $userId);
        $this->assertNotNull($savedUser, "El usuario no fue creado correctamente.");

        $this->entityManager->remove($savedUser);
        $this->entityManager->flush();

        $deletedUser = $this->entityManager->find(User::class, $userId);
        $this->assertNull($deletedUser, "El usuario no fue eliminado correctamente.");
    }
}
