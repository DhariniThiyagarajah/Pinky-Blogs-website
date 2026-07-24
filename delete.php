<?php
/**
 * delete.php - Blog deletion handler
 *
 * Deletes a blog post only if it belongs to the logged-in user.
 * Ownership is verified before deletion.
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$blogId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($blogId <= 0) {
    header('Location: dashboard.php');
    exit;
}

requireBlogOwnership($conn, $blogId);

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare('DELETE FROM blogPost WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $blogId, $userId);
$stmt->execute();
$stmt->close();

header('Location: dashboard.php?deleted=1');
exit;
