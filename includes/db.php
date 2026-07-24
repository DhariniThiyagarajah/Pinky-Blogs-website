<?php
/**
 * db.php - Database connection handler
 *
 * Establishes a mysqli connection to the anime_journal database.
 * Include this file wherever database access is needed.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'anime_journal');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Database connection failed. Please check your configuration.');
}

$conn->set_charset('utf8mb4');
