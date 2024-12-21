<?php

use PHPUnit\Framework\TestCase;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

class AddUserTest extends TestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = DoctrineConnector::getEntityManager();
    }

    protected function tearDown(): void
    {
        $this->entityManager->clear();
        $query = $this->entityManager->createQuery('DELETE FROM MiW\Results\Entity\User u WHERE u.username = :username');
        $query->setParameter('username', 'new_user');
        $query->execute();
    }

    public function testCreateUser()
    {
        $username = 'new_user';
        $email = 'new_user@example.com';
        $password = 'user_password';

        $newUser = new User();
        $newUser->setUsername($username);
        $newUser->setEmail($email);
        $newUser->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $newUser->setEnabled(true);

        // Validar los datos antes de persistir
        $this->assertNotEmpty($newUser->getUsername(), 'El nombre de usuario está vacío.');
        $this->assertNotEmpty($newUser->getEmail(), 'El correo electrónico está vacío.');

        try {
            $this->entityManager->persist($newUser);
            $this->entityManager->flush(); // Aquí puede ocurrir el error
        } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
            $this->fail('Error de conexión a la base de datos: ' . $e->getMessage());
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->fail('Error de Doctrine ORM: ' . $e->getMessage());
        } catch (\Throwable $e) {
            $this->fail('Error inesperado: ' . $e->getMessage());
        }

        // Verificar que el usuario fue guardado correctamente
        $savedUser = $this->entityManager->find(User::class, $newUser->getId());

        $this->assertNotNull($savedUser, 'El usuario no se guardó correctamente.');
        $this->assertEquals($username, $savedUser->getUsername(), 'El nombre de usuario no coincide.');
        $this->assertEquals($email, $savedUser->getEmail(), 'El correo electrónico no coincide.');
        $this->assertTrue(password_verify($password, $savedUser->getPassword()), 'La contraseña no es válida.');
        $this->assertTrue($savedUser->isEnabled(), 'El usuario no está habilitado.');
    }
}
