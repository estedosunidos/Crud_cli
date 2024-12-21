<?php

require_once __DIR__ . '/../../bootstrap.php'; 

use MiW\Results\Entity\User;

// Validar los parámetros recibidos
if ($argc < 4) {
    echo "Usage: php create_user_admin.php <username> <email> <password>" . PHP_EOL;
    exit(1);
}

$username = $argv[1];
$email = $argv[2];
$password = $argv[3];

// Obtener el EntityManager
$entityManager = require_once __DIR__ . '/../../bootstrap.php';

$user = new User();
$user->setUsername($username)
    ->setEmail($email)
    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
    ->setEnabled(true)
    ->setAdmin(true);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo "Usuario administrador creado con ID: " . $user->getId() . PHP_EOL;
} catch (Exception $e) {
    echo "Error al crear el usuario administrador: " . $e->getMessage() . PHP_EOL;
}
