<?php

global $entityManager;
require_once __DIR__ . '/../../bootstrap.php';

use MiW\Results\Entity\User;

// Validar los parámetros recibidos
if ($argc < 2) {
    echo "Usage: php show_users.php <user_id>" . PHP_EOL;
    exit(1);
}

$userId = (int) $argv[1];

// Buscar el usuario con el ID proporcionado
$user = $entityManager->find(User::class, $userId);

// Comprobar si el usuario existe
if (!$user) {
    echo "Usuario con ID $userId no encontrado." . PHP_EOL;
    exit(1);
}

// Mostrar la información del usuario
echo sprintf(
    "ID: %d | Username: %s | Email: %s | Enabled: %d" . PHP_EOL,
    $user->getId(),
    $user->getUsername(),
    $user->getEmail(),
    $user->isEnabled() ? 1 : 0 // Asegura que se imprima 1 o 0
);
