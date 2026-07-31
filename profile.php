<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

requireLogin();
$userId = (int) $_SESSION['user_id'];
$success = isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['deleted']) || isset($_GET['profile_updated']);

$stmt = $conn->prepare(
    'SELECT username, email, profile_image, cover_image, description, discord, youtube, x_link, spotify
     FROM user WHERE id = ?'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare(
    'SELECT id, title, created_at, updated_at FROM blogPost
     WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$totalPosts = count($blogs);

$imagePath = static function (?string $filename, string $fallback): string {
    if ($filename && file_exists(__DIR__ . '/assests/images/' . basename($filename))) {
        return 'assests/images/' . rawurlencode(basename($filename));
    }
    return 'assests/images/' . $fallback;
};
$hasCustomCover = !empty($user['cover_image'])
    && basename($user['cover_image']) !== 'default-cover.jpg'
    && file_exists(__DIR__ . '/assests/images/' . basename($user['cover_image']));
?>

<div class="profile-page-shell">
    <section class="profile-window">
        <div class="profile-window-bar">
            <span>PROFILE_CARD.EXE</span>
            <span class="profile-window-controls" aria-hidden="true">— □ ×</span>
        </div>
        <div class="profile-cover<?= $hasCustomCover ? '' : ' is-default-cover' ?>">
            <?php if ($hasCustomCover): ?>
                <img src="<?= e($imagePath($user['cover_image'], 'default-cover.jpg')) ?>" alt="<?= e($user['username']) ?>'s cover image">
            <?php else: ?>
                <div class="animated-profile-cover" role="img" aria-label="Animated pink sky with drifting clouds, stars and hearts">
                    <span class="cover-cloud cloud-one"></span>
                    <span class="cover-cloud cloud-two"></span>
                    <span class="cover-cloud cloud-three"></span>
                    <span class="cover-sparkle sparkle-one">✦</span>
                    <span class="cover-sparkle sparkle-two">♡</span>
                    <span class="cover-sparkle sparkle-three">✧</span>
                    <div class="cover-message"><strong>my cozy corner</strong><small>dream · write · remember</small></div>
                </div>
            <?php endif; ?>
            <a href="edit_profile.php" class="cover-edit-link">Edit profile</a>
        </div>

        <div class="profile-paper">
            <?php if ($success): ?>
                <div class="alert alert-success profile-alert">
                    <?= isset($_GET['profile_updated']) ? 'Your profile has been updated.' : 'Your journal changes were saved.' ?>
                </div>
            <?php endif; ?>

            <aside class="profile-identity">
                <div class="profile-picture">
                    <img src="<?= e($imagePath($user['profile_image'], 'default-profile.jpg')) ?>" alt="<?= e($user['username']) ?>'s profile photo">
                </div>
                <h1><?= e($user['username']) ?></h1>
                <span class="profile-handle">@<?= e($user['username']) ?></span>
                <p class="profile-email"><?= e($user['email']) ?></p>
                <p class="profile-bio"><?= nl2br(e($user['description'] ?: 'Welcome to my Pinky Blog ♡')) ?></p>
                <a href="edit_profile.php" class="edit-profile-btn">Edit my details</a>

                <div class="social-links" aria-label="Social links">
                    <?php
                    $socials = [
                        ['youtube', 'youtube.jpg', 'YouTube'],
                        ['x_link', 'pinterest.jpg', 'X / Pinterest'],
                        ['discord', 'discord.jpg', 'Discord'],
                        ['spotify', 'spotify.jpg', 'Spotify'],
                    ];
                    foreach ($socials as [$field, $icon, $label]):
                        $link = trim((string) ($user[$field] ?? ''));
                        $isSafeLink = filter_var($link, FILTER_VALIDATE_URL)
                            && in_array(strtolower((string) parse_url($link, PHP_URL_SCHEME)), ['http', 'https'], true);
                    ?>
                        <?php if ($isSafeLink): ?>
                            <a href="<?= e($link) ?>" target="_blank" rel="noopener noreferrer" class="social-frame" title="<?= e($label) ?>">
                                <img src="assests/images/<?= e($icon) ?>" alt="<?= e($label) ?>">
                            </a>
                        <?php else: ?>
                            <span class="social-frame is-empty" title="Add <?= e($label) ?> in Edit profile">
                                <img src="assests/images/<?= e($icon) ?>" alt="">
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </aside>

            <main class="profile-journal">
                <div class="journal-welcome">
                    <div>
                        <p class="eyebrow">Welcome</p>
                        <h2>My little journal</h2>
                        <p>Keep your stories, reviews, memories and favourite moments together.</p>
                    </div>
                    <div class="profile-stats">
                        <span><strong><?= $totalPosts ?></strong> posts</span>
                    </div>
                </div>

                <div class="profile-mood-strip" aria-label="Profile highlights">
                    <span>cozy writer</span><span>anime lover</span><span>journal keeper</span>
                </div>

                <div class="journal-heading">
                    <div>
                        <p class="eyebrow">Journal entries</p>
                        <h2>My latest blogs</h2>
                    </div>
                    <a href="create.php" class="btn btn-primary">Create Blog</a>
                </div>

                <?php if (empty($blogs)): ?>
                    <div class="empty-state profile-empty">
                        <p>You haven't written any blog posts yet.</p>
                        <a href="create.php" class="btn btn-primary">Create Your First Blog</a>
                    </div>
                <?php else: ?>
                    <div class="dashboard-list profile-post-list">
                        <?php foreach ($blogs as $blog): ?>
                            <article class="dashboard-item profile-post">
                                <div>
                                    <h3><?= e($blog['title']) ?></h3>
                                    <p class="meta">Published <?= e(formatDate($blog['created_at'])) ?><?php if ($blog['updated_at'] !== $blog['created_at']): ?> · Updated <?= e(formatDate($blog['updated_at'])) ?><?php endif; ?></p>
                                </div>
                                <div class="actions">
                                    <a href="view.php?id=<?= (int) $blog['id'] ?>" class="btn btn-secondary btn-small">View</a>
                                    <a href="edit.php?id=<?= (int) $blog['id'] ?>" class="btn btn-primary btn-small">Edit</a>
                                    <form method="POST" action="delete.php" data-confirm-delete data-blog-title="<?= e($blog['title']) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $blog['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                    </form>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
