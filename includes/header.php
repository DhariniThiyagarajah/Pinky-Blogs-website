<?php
/**
 * header.php - Shared page header and site banner
 *
 * Renders the kawaii-styled banner, top navigation,
 * and opens the main content wrapper.
 */

require_once __DIR__ . '/auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
$isHome = $currentPage === 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — Pinky Blog' : 'Pinky Blog' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&family=VT323&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?= (int) filemtime(__DIR__ . '/../css/style.css') ?>">
<link rel="stylesheet" href="css/pinky-theme.css?v=<?= (int) filemtime(__DIR__ . '/../css/pinky-theme.css') ?>">
<link rel="stylesheet" href="css/cookies.css?v=<?= (int) filemtime(__DIR__ . '/../css/cookies.css') ?>">

<?php if (in_array(basename($_SERVER['PHP_SELF']), ['profile.php', 'edit_profile.php'], true)): ?>
<link rel="stylesheet" href="css/profile.css?v=<?= (int) filemtime(__DIR__ . '/../css/profile.css') ?>">
<?php endif; ?>
<?php if (basename($_SERVER['PHP_SELF']) === 'blogs.php'): ?>
<link rel="stylesheet" href="css/blogs.css?v=<?= (int) filemtime(__DIR__ . '/../css/blogs.css') ?>">
<?php endif; ?>
<?php if (basename($_SERVER['PHP_SELF']) === 'view.php'): ?>
<link rel="stylesheet" href="css/view.css?v=<?= (int) filemtime(__DIR__ . '/../css/view.css') ?>">
<?php endif; ?>
<?php if (in_array(basename($_SERVER['PHP_SELF']), ['create.php', 'edit.php'], true)): ?>
<link rel="stylesheet" href="css/create.css?v=<?= (int) filemtime(__DIR__ . '/../css/create.css') ?>">
<?php endif; ?>
<?php if (basename($_SERVER['PHP_SELF']) === 'aboutme.php'): ?>
<link rel="stylesheet" href="css/aboutme.css?v=<?= (int) filemtime(__DIR__ . '/../css/aboutme.css') ?>">
<?php endif; ?>
</head>
<body class="<?= $isHome ? 'page-home' : 'page-inner' ?>">
  
<div class="float-field">

    <div class="floater heart" style="left:5%; animation-duration:12s; animation-delay:0s;"></div>
    <div class="floater photo-chip" style="left:12%; animation-duration:18s; animation-delay:2s;"></div>

    <div class="floater heart" style="left:22%; animation-duration:15s; animation-delay:4s;"></div>
    <div class="floater photo-chip" style="left:32%; animation-duration:20s; animation-delay:1s;"></div>

    <div class="floater heart" style="left:45%; animation-duration:13s; animation-delay:3s;"></div>
    <div class="floater photo-chip" style="left:55%; animation-duration:22s; animation-delay:5s;"></div>

    <div class="floater heart" style="left:68%; animation-duration:16s; animation-delay:2s;"></div>
    <div class="floater photo-chip" style="left:78%; animation-duration:19s; animation-delay:6s;"></div>

    <div class="floater heart" style="left:90%; animation-duration:14s; animation-delay:1s;"></div>

</div>

<?php if ($currentPage !== 'blogs.php'): ?>
    <header class="pinky-header">

        <div class="pinky-logo">
            ⋆｡°✩ Pinky Blog ✩°｡⋆
        </div>

        <p class="pinky-tagline">
            a cozy place for little stories ♡
        </p>

    </header>
<?php endif; ?>

<nav class="pinky-nav">

    <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
        <small>Home</small>
    </a>


    <a href="blogs.php" class="<?= in_array($currentPage, ['blogs.php', 'view.php'], true) ? 'active' : '' ?>">
        <small>Blogs</small>
    </a>


    <a href="game.html" class="<?= $currentPage === 'game.html' ? 'active' : '' ?>">
        <small>Game</small>
    </a>


    <?php if (isLoggedIn()): ?>

    <a href="profile.php" class="<?= in_array($currentPage, ['profile.php', 'edit_profile.php', 'create.php', 'edit.php'], true) ? 'active' : '' ?>">
        <small>Dashboard</small>
    </a>

    <a href="logout.php">
        <small>Logout</small>
    </a>

    <?php else: ?>

    <a href="login.php">
        <small>Login</small>
    </a>

    <?php endif; ?>

</nav>
    <main class="main-content">
