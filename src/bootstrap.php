<?php
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;


require_once __DIR__ . '/../vendor/autoload.php';

// Configuración para el EntityManager
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/Entity'], // Ruta donde están tus entidades
    isDevMode: true               // Modo de desarrollo
);

// Configuración de conexión a la base de datos
$conn = [
    'dbname'   => $_ENV['DATABASE_NAME'] ?? 'db_name',
    'user'     => $_ENV['DATABASE_USER'] ?? 'root',
    'password' => $_ENV['DATABASE_PASSWD'] ?? '',
    'host'     => $_ENV['DATABASE_HOST'] ?? '127.0.0.1',
    'port'     => $_ENV['DATABASE_PORT'] ?? 3306,
    'driver'   => $_ENV['DATABASE_DRIVER'] ?? 'pdo_mysql',
];

try {
    // Inicializa la conexión y el EntityManager
    $connection = DriverManager::getConnection($conn, $config);
    $entityManager = new EntityManager($connection, $config);
} catch (Throwable $e) {
    fwrite(STDERR, 'Error al inicializar Doctrine: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
