<?php

// Incluir el archivo bootstrap.php que inicializa el EntityManager
require_once __DIR__ . '/../../bootstrap.php';  // Asegúrate de incluirlo solo una vez

use MiW\Results\Entity\User;

// Validar los parámetros recibidos
if ($argc < 2) {
    echo "Usage: php delete_user.php <user_id>" . PHP_EOL;
    exit(1);
}

// Obtener el ID del usuario
$userId = (int) $argv[1];

// Verificar si el EntityManager es válido
if (!$entityManager) {
    echo "Error al obtener el EntityManager." . PHP_EOL;
    exit(1);
}

// Buscar el usuario
$user = $entityManager->find(User::class, $userId);

if (!$user) {
    echo "Usuario con ID $userId no encontrado." . PHP_EOL;
    exit(1);
}

try {
    // Eliminar el usuario
    $entityManager->remove($user);
    $entityManager->flush();
    echo "Usuario con ID $userId eliminado." . PHP_EOL;
} catch (Exception $e) {
    echo "Error al eliminar el usuario: " . $e->getMessage() . PHP_EOL;
}
