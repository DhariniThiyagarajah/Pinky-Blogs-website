<?php
/**
 * create.php - Blog creation page
 *
 * Allows logged-in users to create a new blog post
 * with title and content fields.
 */

$pageTitle = 'Create Blog';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/blog_image.php';

requireLogin();

$errors = [];
$title = '';
$content = '';
$thumbnail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (isset($_FILES['thumbnail'])) $thumbnail = saveBlogThumbnail($_FILES['thumbnail'], $errors) ?? '';

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Title must not exceed 200 characters.';
    }

    if ($content === '') {
        $errors[] = 'Content is required.';
    }

    if (empty($errors)) {
        $userId = (int) $_SESSION['user_id'];

        $stmt = $conn->prepare(
            'INSERT INTO blogPost (user_id, title, content, thumbnail) VALUES (?, ?, ?, ?)'
        );

        $stmt->bind_param('isss', $userId, $title, $content, $thumbnail);

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: profile.php?created=1');
            exit;
        }

        $errors[] = 'Failed to create blog post. Please try again.';
        $stmt->close();
    }
}
?>

<div class="inner-layout create-blog-page">
    <div class="inner-main">
        <div class="kawaii-box form-container-wide" style="margin: 0 auto;">
            <div class="box-cap">&#9998; new entry</div>

            <div class="page-header">
                <h1>Create a New Blog</h1>
                <p>Share your thoughts about anime, manga, characters, and more.</p>
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

            <form method="POST" action="create.php" enctype="multipart/form-data" data-validate novalidate>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?= e($title) ?>" maxlength="200" data-required>
                    <span class="field-error">This field is required.</span>
                </div>

                    <div class="form-group">
                        <label for="thumbnail">Blog Thumbnail</label>

                        <input
                            type="file"
                            id="thumbnail"
                            name="thumbnail"
                            data-resize-thumbnail
                            accept="image/png,image/jpeg,image/jpg,image/webp">

                        <small>
                            Upload a cover image (optional). It will be center-cropped to 960 × 600.
                        </small>
                    </div>


                    <div class="form-group">
                        <label for="content">Content</label>

                        <textarea 
                            id="content" 
                            name="content" 
                            data-required 
                            data-char-counter><?= e($content) ?></textarea>

                        <div class="char-counter">0 characters</div>

                        <span class="field-error">
                            This field is required.
                        </span>
                    </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Publish Blog</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
