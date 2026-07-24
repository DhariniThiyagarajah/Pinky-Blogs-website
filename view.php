<?php
/**
 * view.php - Single blog view page
 *
 * Displays the full blog post with title, content,
 * author, created date, and updated date.
 */

$pageTitle = 'View Blog';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$blogId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($blogId <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare(
    'SELECT b.id, b.title, b.content, b.created_at, b.updated_at, b.user_id, u.username
     FROM blogPost b
     JOIN user u ON b.user_id = u.id
     WHERE b.id = ?'
);
$stmt->bind_param('i', $blogId);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    header('Location: index.php');
    exit;
}

$pageTitle = $blog['title'];
$isOwner = isLoggedIn() && (int) $_SESSION['user_id'] === (int) $blog['user_id'];

$countStmt = $conn->prepare('SELECT COUNT(*) as total FROM blogPost');
$countStmt->execute();
$totalPosts = (int) $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();
?>

<div class="inner-layout">
    <div class="inner-main">
        <article class="blog-view">
            <h1><?= e($blog['title']) ?></h1>

            <div class="meta">
                <span>&#9998; By <?= e($blog['username']) ?></span>
                <span>&#128197; Published <?= e(formatDate($blog['created_at'])) ?></span>
                <?php if ($blog['updated_at'] !== $blog['created_at']): ?>
                    <span>&#128260; Updated <?= e(formatDate($blog['updated_at'])) ?></span>
                <?php endif; ?>
            </div>

            <div class="content"><?= e($blog['content']) ?></div>

            <div class="btn-group">
                <a href="index.php" class="btn btn-secondary btn-small">&larr; Back to Home</a>
                <?php if ($isOwner): ?>
                    <a href="edit.php?id=<?= (int) $blog['id'] ?>" class="btn btn-primary btn-small">Edit</a>
                <?php endif; ?>
            </div>
        </article>
    </div>
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
