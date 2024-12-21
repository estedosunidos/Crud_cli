<?php

global $entityManager;
require_once __DIR__ . '/../../bootstrap.php';

// Verificar si se pasan los parámetros necesarios
if ($argc < 2) {
    echo "Uso: php update_user.php <user_id> <new_username> <new_email> <enabled>\n";
    exit(0);
}

$userId = (int) $argv[1];
$newUsername = $argv[2];
$newEmail = $argv[3];
$newEnabled = filter_var($argv[4], FILTER_VALIDATE_BOOLEAN); // Convertir el valor a booleano

$userRepository = $entityManager->getRepository(\MiW\Results\Entity\User::class);

// Buscar el usuario por ID
$user = $userRepository->find($userId);

if (!$user) {
    echo "Usuario con ID $userId no encontrado.\n";
    exit(0);
}

// Actualizar los campos del usuario
$user->setUsername($newUsername);
$user->setEmail($newEmail);
$user->setEnabled($newEnabled);

// Guardar los cambios
$entityManager->flush();

echo "Usuario con ID $userId actualizado con éxito.\n";

