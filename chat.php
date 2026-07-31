<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query(
        'SELECT cm.message, cm.created_at, u.username
         FROM chatMessage cm JOIN user u ON cm.user_id = u.id
         ORDER BY cm.id DESC LIMIT 50'
    );
    $messages = array_reverse($result->fetch_all(MYSQLI_ASSOC));
    echo json_encode(['messages' => array_map(static function (array $message): array {
        return [
            'username' => $message['username'],
            'message' => $message['message'],
            'time' => date('M j, H:i', strtotime($message['created_at'])),
        ];
    }, $messages)], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Please sign in to chat.']);
    exit;
}

if (!isset($_SESSION['chat_csrf']) || !hash_equals($_SESSION['chat_csrf'], $_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Your session expired. Refresh the page and try again.']);
    exit;
}

$message = trim((string) ($_POST['message'] ?? ''));
if ($message === '' || mb_strlen($message) > 300) {
    http_response_code(422);
    echo json_encode(['error' => 'Messages must be between 1 and 300 characters.']);
    exit;
}

$now = time();
if (isset($_SESSION['last_chat_message']) && $now - (int) $_SESSION['last_chat_message'] < 2) {
    http_response_code(429);
    echo json_encode(['error' => 'Please wait a moment before sending again.']);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare('INSERT INTO chatMessage (user_id, message) VALUES (?, ?)');
$stmt->bind_param('is', $userId, $message);
$stmt->execute();
$stmt->close();
$_SESSION['last_chat_message'] = $now;

echo json_encode(['success' => true]);
