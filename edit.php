<?php
/**
 * edit.php - Blog editing page
 *
 * Allows logged-in users to update their own blog posts.
 * Ownership is verified before allowing edits.
 */

$pageTitle = 'Edit Blog';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/blog_image.php';

$blogId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($blogId <= 0) {
    header('Location: profile.php');
    exit;
}

$blog = requireBlogOwnership($conn, $blogId);

$errors = [];
$title = $blog['title'];
$content = $blog['content'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $thumbnail = $blog['thumbnail'] ?? '';

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Title must not exceed 200 characters.';
    }

    if ($content === '') {
        $errors[] = 'Content is required.';
    }

    if (empty($errors) && isset($_FILES['thumbnail'])) {
        $newThumbnail = saveBlogThumbnail($_FILES['thumbnail'], $errors);
        if ($newThumbnail !== null) $thumbnail = $newThumbnail;
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            'UPDATE blogpost SET title = ?, content = ?, thumbnail = ? WHERE id = ? AND user_id = ?'
        );
        $userId = (int) $_SESSION['user_id'];
        $stmt->bind_param('sssii', $title, $content, $thumbnail, $blogId, $userId);

        if ($stmt->execute() && $stmt->affected_rows >= 0) {
            $stmt->close();
            header('Location: profile.php?updated=1');
            exit;
        }

        $errors[] = 'Failed to update blog post. Please try again.';
        $stmt->close();
    }
}
?>

<div class="inner-layout create-blog-page edit-blog-page">
    <div class="inner-main">
        <div class="kawaii-box form-container-wide" style="margin: 0 auto;">
            <div class="box-cap">&#9998; edit entry</div>

            <div class="page-header">
                <h1>Edit Blog</h1>
                <p>Update your blog post below.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 18px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?= $blogId ?>" enctype="multipart/form-data" data-validate novalidate>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?= e($title) ?>" maxlength="200" data-required>
                    <span class="field-error">This field is required.</span>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Replace Blog Thumbnail</label>
                    <?php if ($currentThumbnail = blogThumbnailUrl($blog['thumbnail'] ?? null)): ?>
                        <div class="current-thumbnail"><img src="<?= e($currentThumbnail) ?>" alt="Current thumbnail for <?= e($blog['title']) ?>"><span>Current thumbnail</span></div>
                    <?php endif; ?>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/png,image/jpeg,image/webp" data-resize-thumbnail>
                    <small>Optional. The image will be center-cropped to 960 × 600.</small>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" data-required data-char-counter><?= e($content) ?></textarea>
                    <div class="char-counter">0 characters</div>
                    <span class="field-error">This field is required.</span>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
