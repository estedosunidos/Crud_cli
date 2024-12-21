<?php

require dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 3));
// Cargar el EntityManager
$entityManager = DoctrineConnector::getEntityManager();

// Verificar si se pasan los parámetros necesarios
if ($argc < 4) {
    echo "Usage: php update_result.php <result_id> <new_result_value> <new_timestamp>" . PHP_EOL;
    exit(0);
}

$resultId = (int) $argv[1];
$newResultValue = (int) $argv[2];
$newTimestamp = new DateTime($argv[3]);

// Buscar el resultado por ID
$result = $entityManager->find(Result::class, $resultId);

if (!$result) {
    echo "Resultado con ID $resultId no encontrado." . PHP_EOL;
    exit(0);
}

// Actualizar el resultado
$result->setResult($newResultValue);
$result->setTimestamp($newTimestamp);

try {
    $entityManager->flush();
    echo "Resultado con ID $resultId actualizado." . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}
