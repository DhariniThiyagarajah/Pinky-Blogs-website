<?php
/**
 * sidebar.php - Reusable right-column navigation widget
 *
 * Kawaii-styled site navigation used on the homepage
 * and inner pages for consistent layout.
 */

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="kawaii-sidebar">
    <div class="kawaii-box sidebar-nav">
        <div class="box-cap">&#9733; navigate &#9733;</div>
        <nav class="sidebar-menu">
            <a href="index.php" class="sidebar-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">&#127800; Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">&#128221; My Journal</a>
                <a href="create.php" class="sidebar-link <?= $currentPage === 'create.php' ? 'active' : '' ?>">&#9998; Write New</a>
                <a href="logout.php" class="sidebar-link">&#128275; Logout</a>
            <?php else: ?>
                <a href="login.php" class="sidebar-link <?= $currentPage === 'login.php' ? 'active' : '' ?>">&#128273; Login</a>
                <a href="register.php" class="sidebar-link <?= $currentPage === 'register.php' ? 'active' : '' ?>">&#128150; Register</a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="kawaii-box sidebar-spirit">
        <div class="box-cap">&#127752; journal spirit</div>
        <div class="spirit-scene">
            <div class="spirit-sky"></div>
            <div class="spirit-character">&#128047;</div>
            <div class="spirit-grass"></div>
        </div>
        <div class="spirit-stats">
            <p><span>Mood</span> <strong>Cozy</strong></p>
            <p><span>Posts</span> <strong><?= isset($totalPosts) ? (int) $totalPosts : '—' ?></strong></p>
            <p><span>Energy</span> <strong>&#9733;&#9733;&#9733;&#9734;&#9734;</strong></p>
        </div>
        <div class="spirit-actions">
            <span class="mini-btn">Tea</span>
            <span class="mini-btn">Read</span>
            <span class="mini-btn">Dream</span>
        </div>
    </div>
</aside>
