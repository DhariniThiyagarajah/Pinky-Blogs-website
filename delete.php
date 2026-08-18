<?php
/**
 * delete.php - Blog deletion handler
 *
 * Blog owners may delete their own posts. Administrators may delete any post.
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$blogId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($blogId <= 0) {
    header('Location: profile.php');
    exit;
}

requireBlogDeleteAccess($conn, $blogId);

$stmt = $conn->prepare('DELETE FROM blogpost WHERE id = ?');
$stmt->bind_param('i', $blogId);
$stmt->execute();
$stmt->close();

header('Location: ' . (isAdmin() ? 'blogs.php?deleted=1' : 'profile.php?deleted=1'));
exit;
