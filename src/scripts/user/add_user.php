<?php


global $entityManager;
require_once __DIR__ . '/../../bootstrap.php';

$userRepository = $entityManager->getRepository(\MiW\Results\Entity\User::class);


$newUser = new \MiW\Results\Entity\User();
$newUser->setUsername('new_user1');
$newUser->setEmail('new_use1r@example.com');
$newUser->setPassword(password_hash('user_password', PASSWORD_BCRYPT));
$newUser->setEnabled(true);

$entityManager->persist($newUser);
$entityManager->flush();

echo "Nuevo usuario agregado con éxito.\n";
