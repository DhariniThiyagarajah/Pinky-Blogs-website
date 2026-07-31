<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/comments.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: blogs.php');
    exit;
}

$blogId = (int) ($_POST['blog_id'] ?? 0);
$comment = trim((string) ($_POST['comment'] ?? ''));
$token = (string) ($_POST['comment_token'] ?? '');

if ($blogId <= 0 || !hash_equals(commentToken(), $token)) {
    header('Location: blogs.php');
    exit;
}

if ($comment === '') {
    header('Location: view.php?id=' . $blogId . '&comment_error=empty#comments');
    exit;
}

if (mb_strlen($comment) > 500) {
    header('Location: view.php?id=' . $blogId . '&comment_error=long#comments');
    exit;
}

ensureCommentsTable($conn);
$stmt = $conn->prepare('INSERT INTO blogComment (blog_id, user_id, comment) VALUES (?, ?, ?)');
$userId = (int) $_SESSION['user_id'];
$stmt->bind_param('iis', $blogId, $userId, $comment);

if (!$stmt->execute()) {
    $stmt->close();
    header('Location: view.php?id=' . $blogId . '&comment_error=save#comments');
    exit;
}

$stmt->close();
header('Location: view.php?id=' . $blogId . '&comment_added=1#comments');
exit;
