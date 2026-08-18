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
require_once __DIR__ . '/includes/comments.php';
require_once __DIR__ . '/includes/blog_image.php';

$blogId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($blogId <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare(
    'SELECT b.id, b.title, b.content, b.thumbnail, b.created_at, b.updated_at, b.user_id, u.username
     FROM blogpost b
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
$mayDelete = canDeleteBlog($blog);

ensureCommentsTable($conn);
$stmt = $conn->prepare(
    'SELECT c.comment, c.created_at, u.username, u.profile_image
     FROM blogcomment c
     JOIN user u ON c.user_id = u.id
     WHERE c.blog_id = ?
     ORDER BY c.created_at DESC, c.id DESC'
);
$stmt->bind_param('i', $blogId);
$stmt->execute();
$comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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

            <?php if ($viewThumbnail = blogThumbnailUrl($blog['thumbnail'])): ?>
                <figure class="blog-view-thumbnail">
                    <img src="<?= e($viewThumbnail) ?>" alt="Thumbnail for <?= e($blog['title']) ?>">
                </figure>
            <?php endif; ?>

            <div class="content"><?= e($blog['content']) ?></div>

            <div class="btn-group">
                <a href="index.php" class="btn btn-secondary btn-small">&larr; Back to Home</a>
                <?php if ($isOwner): ?>
                    <a href="edit.php?id=<?= (int) $blog['id'] ?>" class="btn btn-primary btn-small">Edit</a>
                <?php endif; ?>
                <?php if ($mayDelete): ?>
                    <form method="POST" action="delete.php" data-confirm-delete data-blog-title="<?= e($blog['title']) ?>">
                        <input type="hidden" name="id" value="<?= (int) $blog['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-small"><?= isAdmin() && !$isOwner ? 'Admin Delete' : 'Delete' ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </article>

        <section class="blog-comments" id="comments">
            <div class="comments-heading">
                <div><p>COMMUNITY_NOTES.TXT</p><h2>Comments</h2></div>
                <span><?= count($comments) ?> <?= count($comments) === 1 ? 'comment' : 'comments' ?></span>
            </div>

            <?php if (isset($_GET['comment_added'])): ?>
                <p class="comment-message success">Your comment was added.</p>
            <?php elseif (isset($_GET['comment_error'])): ?>
                <p class="comment-message error"><?php
                    echo match ($_GET['comment_error']) {
                        'empty' => 'Please write a comment first.',
                        'long' => 'Please keep your comment under 500 characters.',
                        default => 'The comment could not be saved. Please try again.'
                    };
                ?></p>
            <?php endif; ?>

            <?php if (isLoggedIn()): ?>
                <form class="comment-form" method="POST" action="comment.php">
                    <input type="hidden" name="blog_id" value="<?= (int) $blogId ?>">
                    <input type="hidden" name="comment_token" value="<?= e(commentToken()) ?>">
                    <label for="comment">Join the conversation</label>
                    <textarea id="comment" name="comment" rows="4" maxlength="500" placeholder="Write something kind and clear..." required></textarea>
                    <div><small>Up to 500 characters</small><button class="btn btn-primary" type="submit">Post comment</button></div>
                </form>
            <?php else: ?>
                <div class="comment-login">Want to join the conversation? <a href="login.php">Log in to comment</a>.</div>
            <?php endif; ?>

            <div class="comment-list">
                <?php if (!$comments): ?>
                    <p class="no-comments">No comments yet. Be the first to say hello!</p>
                <?php else: ?>
                    <?php foreach ($comments as $item): ?>
                        <article class="comment-card">
                            <div class="comment-avatar"><?= e(mb_strtoupper(mb_substr($item['username'], 0, 1))) ?></div>
                            <div><header><strong><?= e($item['username']) ?></strong><time><?= e(formatDate($item['created_at'])) ?></time></header><p><?= nl2br(e($item['comment'])) ?></p></div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
