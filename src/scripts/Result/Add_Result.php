<?php

require dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 3));

if ($argc < 3 || $argc > 4) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$newResult    = (int) $argv[1];
$userId       = (int) $argv[2];
$newTimestamp = $argv[3] ?? new DateTime('now');

$entityManager = DoctrineConnector::getEntityManager();

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy([ 'id' => $userId ]);
if (!$user instanceof User) {
    echo "Usuario $userId no encontrado" . PHP_EOL;
    exit(0);
}

$result = new Result($newResult, $user, $newTimestamp);
try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo 'Created Result with ID ' . $result->getId()
        . ' USER ' . $user->getUsername() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}
