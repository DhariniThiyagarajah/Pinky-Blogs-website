<?php
/** Database connection using private environment variables. */

require_once __DIR__ . '/bootstrap.php';

$requestHost = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$isLocalDatabase = PHP_SAPI === 'cli'
    || $requestHost === 'localhost'
    || strpos($requestHost, 'localhost:') === 0
    || $requestHost === '127.0.0.1'
    || strpos($requestHost, '127.0.0.1:') === 0;

$environmentHost = environmentValue('DB_HOST');
$environmentPort = environmentValue('DB_PORT');
$environmentName = environmentValue('DB_NAME');
$environmentUser = environmentValue('DB_USERNAME') ?: environmentValue('DB_USER');
$environmentPassword = environmentValue('DB_PASSWORD');
$hasEnvironmentDatabase = $environmentHost !== false
    && $environmentName !== false
    && $environmentUser !== false
    && $environmentPassword !== false;

if ($isLocalDatabase) {
    $databaseConfig = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'anime_journal',
        'port' => 3306,
    ];
} elseif ($hasEnvironmentDatabase) {
    $databaseConfig = [
        'host' => $environmentHost,
        'username' => $environmentUser,
        'password' => $environmentPassword,
        'database' => $environmentName,
        'port' => $environmentPort !== false ? (int) $environmentPort : 3306,
    ];
} else {
    error_log('Pinky Blog database environment variables are missing.');
    die('Database configuration is unavailable.');
}

try {
    $conn = new mysqli(
        $databaseConfig['host'],
        $databaseConfig['username'],
        $databaseConfig['password'],
        $databaseConfig['database'],
        (int) ($databaseConfig['port'] ?? 3306)
    );
} catch (mysqli_sql_exception $exception) {
    error_log('Pinky Blog database error: ' . $exception->getMessage());
    die('Database connection failed (code ' . (int) $exception->getCode() . ').');
}

if ($conn->connect_error) {
    die('Database connection failed. Please check your configuration.');
}

$conn->set_charset('utf8mb4');
