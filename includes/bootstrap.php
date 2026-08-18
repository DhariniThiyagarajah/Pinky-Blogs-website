<?php
/** Loads private environment settings and starts a hardened PHP session. */

function environmentValue(string $name, $default = false)
{
    $value = getenv($name);
    if ($value !== false) {
        return $value;
    }

    if (array_key_exists($name, $_ENV)) {
        return $_ENV[$name];
    }

    return $_SERVER[$name] ?? $default;
}

function loadEnvironment(string $file): void
{
    if (!is_file($file) || !is_readable($file)) {
        return;
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES);
    if (!is_array($lines)) {
        throw new RuntimeException('The environment configuration could not be read.');
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$name, $value] = array_map('trim', explode('=', $line, 2));
        if (!preg_match('/^[A-Z][A-Z0-9_]*$/', (string) $name) || environmentValue((string) $name) !== false) {
            continue;
        }

        $length = strlen($value);
        if ($length >= 2 && (($value[0] === '"' && $value[$length - 1] === '"')
            || ($value[0] === "'" && $value[$length - 1] === "'"))) {
            $value = substr($value, 1, -1);
        }

        if (function_exists('putenv')) {
            putenv($name . '=' . $value);
        }
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

$environmentFile = dirname(__DIR__) . '/.env';
if (is_file($environmentFile)) {
    loadEnvironment($environmentFile);
}

// Some shared hosts reserve or silently strip dotfiles. Use the protected
// hosting copy only when the primary .env did not provide database settings.
if (environmentValue('DB_HOST') === false) {
    loadEnvironment(__DIR__ . '/private.php');
}

if (session_status() === PHP_SESSION_NONE) {
    if (PHP_SAPI === 'cli') {
        session_save_path(sys_get_temp_dir());
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');

    session_name(environmentValue('SESSION_COOKIE_NAME', 'pinky_session'));
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
