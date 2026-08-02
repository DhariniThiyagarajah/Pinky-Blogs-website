<?php
/** OAuth login callback for Google, Discord, and GitHub. */
session_start();
require __DIR__ . '/includes/db.php';

function oauthFail(string $message): void
{
    http_response_code(400);
    $safe = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login unavailable</title></head>';
    echo '<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#ffedf7;color:#4a3f66;font-family:Arial,sans-serif"><main style="max-width:440px;padding:32px;text-align:center;background:#fffafd;border:2px solid #f3b4d2;border-radius:22px"><h1>Social login unavailable</h1><p>' . $safe . '</p><a href="login.php" style="color:#b9578c;font-weight:700">Return to login</a></main></body></html>';
    exit;
}

function oauthRequest(string $url, array $options = []): array
{
    if (!function_exists('curl_init')) {
        oauthFail('The server does not have the PHP cURL extension enabled.');
    }

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => $options['headers'] ?? ['Accept: application/json'],
    ]);

    if (isset($options['post'])) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($options['post']));
    }

    $body = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($body === false || $status < 200 || $status >= 300) {
        error_log('OAuth request failed: HTTP ' . $status . ' ' . $error);
        oauthFail('The login provider did not complete the request. Please try again.');
    }

    $data = json_decode($body, true);
    if (!is_array($data)) {
        oauthFail('The login provider returned an invalid response.');
    }
    return $data;
}

function uniqueOauthUsername(mysqli $conn, string $preferred): string
{
    $base = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($preferred));
    $base = trim(substr($base ?: 'pinky_user', 0, 42), '_');
    if (strlen($base) < 3) {
        $base = 'pinky_user';
    }

    $candidate = $base;
    $number = 1;
    $stmt = $conn->prepare('SELECT id FROM user WHERE username = ? LIMIT 1');
    while (true) {
        $stmt->bind_param('s', $candidate);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            $stmt->close();
            return $candidate;
        }
        $candidate = substr($base, 0, 42) . '_' . $number++;
    }
}

$provider = strtolower((string) ($_GET['provider'] ?? ''));
$providers = [
    'google' => [
        'client_id' => getenv('GOOGLE_CLIENT_ID'),
        'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
        'authorize' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'token' => 'https://oauth2.googleapis.com/token',
        'scope' => 'openid email profile',
    ],
    'discord' => [
        'client_id' => getenv('DISCORD_CLIENT_ID'),
        'client_secret' => getenv('DISCORD_CLIENT_SECRET'),
        'authorize' => 'https://discord.com/oauth2/authorize',
        'token' => 'https://discord.com/api/oauth2/token',
        'scope' => 'identify email',
    ],
    'github' => [
        'client_id' => getenv('GITHUB_CLIENT_ID'),
        'client_secret' => getenv('GITHUB_CLIENT_SECRET'),
        'authorize' => 'https://github.com/login/oauth/authorize',
        'token' => 'https://github.com/login/oauth/access_token',
        'scope' => 'read:user user:email',
    ],
];

if (!isset($providers[$provider])) {
    oauthFail('Unknown login provider.');
}

$config = $providers[$provider];
if (!$config['client_id'] || !$config['client_secret']) {
    oauthFail(ucfirst($provider) . ' login has not been configured by the site owner yet.');
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? '';
$path = strtok($_SERVER['REQUEST_URI'] ?? '/oauth.php', '?');
$redirectUri = $scheme . '://' . $host . $path . '?provider=' . rawurlencode($provider);

if (!isset($_GET['code'])) {
    $state = bin2hex(random_bytes(24));
    $_SESSION['oauth_state'] = $state;
    $_SESSION['oauth_provider'] = $provider;
    $query = http_build_query([
        'client_id' => $config['client_id'],
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => $config['scope'],
        'state' => $state,
    ]);
    header('Location: ' . $config['authorize'] . '?' . $query);
    exit;
}

if (!hash_equals((string) ($_SESSION['oauth_state'] ?? ''), (string) ($_GET['state'] ?? ''))
    || ($_SESSION['oauth_provider'] ?? '') !== $provider) {
    oauthFail('The login request expired or could not be verified. Please try again.');
}
unset($_SESSION['oauth_state'], $_SESSION['oauth_provider']);

$token = oauthRequest($config['token'], [
    'post' => [
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'code' => $_GET['code'],
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri,
    ],
    'headers' => ['Accept: application/json'],
]);
$accessToken = (string) ($token['access_token'] ?? '');
if ($accessToken === '') {
    oauthFail('The login provider did not return an access token.');
}

$headers = ['Accept: application/json', 'Authorization: Bearer ' . $accessToken, 'User-Agent: Pinky-Blog'];
if ($provider === 'google') {
    $profile = oauthRequest('https://openidconnect.googleapis.com/v1/userinfo', ['headers' => $headers]);
    $email = (string) ($profile['email'] ?? '');
    $name = (string) ($profile['name'] ?? $email);
    if (isset($profile['email_verified']) && !$profile['email_verified']) {
        oauthFail('Please verify your Google email address before logging in.');
    }
} elseif ($provider === 'discord') {
    $profile = oauthRequest('https://discord.com/api/users/@me', ['headers' => $headers]);
    $email = (string) ($profile['email'] ?? '');
    $name = (string) ($profile['global_name'] ?? $profile['username'] ?? $email);
    if (isset($profile['verified']) && !$profile['verified']) {
        oauthFail('Please verify your Discord email address before logging in.');
    }
} else {
    $profile = oauthRequest('https://api.github.com/user', ['headers' => $headers]);
    $name = (string) ($profile['name'] ?? $profile['login'] ?? 'github_user');
    $email = (string) ($profile['email'] ?? '');
    if ($email === '') {
        $emails = oauthRequest('https://api.github.com/user/emails', ['headers' => $headers]);
        foreach ($emails as $item) {
            if (!empty($item['primary']) && !empty($item['verified'])) {
                $email = (string) $item['email'];
                break;
            }
        }
    }
}

$email = strtolower(trim($email));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    oauthFail('Your account did not provide a verified email address.');
}

$stmt = $conn->prepare('SELECT id, username FROM user WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    $username = uniqueOauthUsername($conn, $name);
    $password = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO user (username, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $email, $password);
    $stmt->execute();
    $user = ['id' => $conn->insert_id, 'username' => $username];
    $stmt->close();
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];
header('Location: index.php');
exit;
