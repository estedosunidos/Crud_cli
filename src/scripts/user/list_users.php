<?php

global $entityManager;
require_once __DIR__ . '/../../bootstrap.php';

$userRepository = $entityManager->getRepository(\MiW\Results\Entity\User::class);

// Obtener todos los usuarios
$users = $userRepository->findAll();

if (empty($users)) {
    echo "No hay usuarios registrados.\n";
} else {
    echo "Listado de usuarios:\n";
    foreach ($users as $user) {
        echo "ID: " . $user->getId() . " - Username: " . $user->getUsername() . " - Email: " . $user->getEmail() . " - Habilitado: " . ($user->isEnabled() ? 'Sí' : 'No') . "\n";
    }
}
