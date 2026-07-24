<?php
/**
 * profile.php - User Profile
 *
 * Shows user information and their blog posts.
 * Provides Create, Edit, View and Delete actions.
 */

$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

requireLogin();
$userId = (int) $_SESSION['user_id'];
$success = isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['deleted']);

$stmt = $conn->prepare(
    "SELECT username, profile_image, cover_image, description, discord, youtube, x_link, spotify
     FROM user
     WHERE id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();

$stmt = $conn->prepare(
    'SELECT id, title, created_at, updated_at
     FROM blogPost
     WHERE user_id = ?
     ORDER BY created_at DESC'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalPosts = count($blogs);
?>

    <div class="profile-layout">

    <div class="profile-cover">
        <img src="<?= !empty($user['cover_image']) 
            ? 'assests/images/' . e($user['cover_image']) 
            : 'assests/images/default-cover.jpg' ?>" 
            alt="Cover Image">
    </div>


    <div class="profile-content">

        <div class="profile-sidebar">   

            <div class="profile-card">

            <div class="profile-picture">
                <img src="<?= !empty($user['profile_image']) && file_exists(__DIR__ . '/assests/images/' . $user['profile_image'])
                    ? 'assests/images/' . e($user['profile_image'])
                    : 'assests/images/default-profile.jpg' ?>"
                    alt="Profile">
            </div>
            <h2>
                <?= e($user['username']) ?>
            </h2>

            <p>
                <?= e($user['description'] ?? "Welcome to my Pinky Blog ♡") ?>
            </p>
            <a href="edit_profile.php" class="edit-profile-btn">
                Edit Profile
            </a>
                <div class="social-links">
                   <a href="<?= e($user['youtube'] ?? '#') ?>" target="_blank" class="social-frame">
                        <img src="assests/images/youtube.jpg" alt="YouTube">
                    </a> 

                    <a href="<?= e($user['x_link'] ?? '#') ?>" target="_blank" class="social-frame">
                        <img src="assests/images/pinterest.jpg" alt="Pinterest">
                    </a>

                    <a href="<?= e($user['discord'] ?? '#') ?>" target="_blank" class="social-frame">
                        <img src="assests/images/discord.jpg" alt="Discord">
                    </a>

                    <a href="<?= e($user['spotify'] ?? '#') ?>" target="_blank" class="social-frame">
                        <img src="assests/images/spotify.jpg" alt="Spotify">
                    </a>
                </div>
        </div> <!-- profile-card -->
           <div class="profile-decorations">
    <img src="assests/images/stamp1.png" alt="">
    <img src="assests/images/stamp2.png" alt="">
</div>           
        </div> <!-- close profile-sidebar -->
        
        </div>
        </div>
        
</div>
        </div>
        
        <div class="profile-main">
             <div class="kawaii-box">
        
            <div class="box-cap">
                ♡ my profile & journals
            </div>

            <div class="dashboard-header">
                <div class="page-header" style="margin-bottom: 0;">
                    <h1>My Profile ♡</h1>
                    <p>
                    Welcome back, <?= e($_SESSION['username']) ?>.
                    Here you can manage your journals and account.
                    </p>
                </div>
                <a href="create.php" class="btn btn-primary">Create Blog</a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php if (isset($_GET['created'])): ?>
                        Your blog post has been created successfully.
                    <?php elseif (isset($_GET['updated'])): ?>
                        Your blog post has been updated successfully.
                    <?php elseif (isset($_GET['deleted'])): ?>
                        Your blog post has been deleted successfully.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

                <?php if (empty($blogs)): ?>

                    <div class="empty-state">
                        <div class="icon" aria-hidden="true">&#128221;</div>
                        <p>You haven't written any blog posts yet.</p>
                        <a href="create.php" class="btn btn-primary">
                            Create Your First Blog
                        </a>
                    </div>

                <?php else: ?>
                    <div class="dashboard-list">

                        <?php foreach ($blogs as $blog): ?>

                            <div class="dashboard-item">

                                <div>
                                    <h3><?= e($blog['title']) ?></h3>

                                    <p class="meta">
                                        Published <?= e(formatDate($blog['created_at'])) ?>

                                        <?php if ($blog['updated_at'] !== $blog['created_at']): ?>
                                            &middot; Updated <?= e(formatDate($blog['updated_at'])) ?>
                                        <?php endif; ?>

                                    </p>
                                </div>

                                <div class="actions">
                                    <a href="view.php?id=<?= (int)$blog['id'] ?>" class="btn btn-secondary btn-small">View</a>
                                    <a href="edit.php?id=<?= (int)$blog['id'] ?>" class="btn btn-primary btn-small">Edit</a>

                                    <form method="POST" action="delete.php" style="display:inline;"
                                        data-confirm-delete
                                        data-blog-title="<?= e($blog['title']) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$blog['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                    </form>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                                <?php endif; ?>

        </div> <!-- kawaii-box -->
    </div> <!-- profile-main -->

</div> <!-- profile-content -->
</div> <!-- profile-layout -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
