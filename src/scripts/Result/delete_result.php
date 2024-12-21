<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 3));
// Cargar el EntityManager
$entityManager = DoctrineConnector::getEntityManager();

// Verificar si se pasa el ID del resultado
if ($argc < 2) {
    echo "Usage: php delete_result.php <result_id>" . PHP_EOL;
    exit(0);
}

$resultId = (int) $argv[1];

// Buscar el resultado por ID
$result = $entityManager->find(Result::class, $resultId);

if (!$result) {
    echo "Resultado con ID $resultId no encontrado." . PHP_EOL;
    exit(0);
}

// Eliminar el resultado
try {
    $entityManager->remove($result);
    $entityManager->flush();
    echo "Resultado con ID $resultId eliminado." . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}
