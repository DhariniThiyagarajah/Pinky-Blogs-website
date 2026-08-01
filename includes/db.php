<?php
/** Database connection for local XAMPP and the live InfinityFree website. */

$requestHost = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$isLocalDatabase = PHP_SAPI === 'cli'
    || $requestHost === 'localhost'
    || strpos($requestHost, 'localhost:') === 0
    || $requestHost === '127.0.0.1'
    || strpos($requestHost, '127.0.0.1:') === 0;

if ($isLocalDatabase) {
    $databaseConfig = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'anime_journal',
        'port' => 3306,
    ];
} else {
    $databaseConfig = require __DIR__ . '/db.infinityfree.php';

    if (($databaseConfig['password'] ?? '') === 'PASTE_YOUR_MYSQL_PASSWORD_HERE') {
        die('The live database password has not been configured yet.');
    }
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
