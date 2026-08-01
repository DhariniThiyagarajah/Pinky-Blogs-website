<?php
/**
 * auth.php - Session authentication helpers
 *
 * Provides functions to check login status, protect pages,
 * and verify blog ownership before edit/delete operations.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is currently logged in.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Redirect to login if user is not authenticated.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Redirect to dashboard if user is already logged in.
 */
function redirectIfLoggedIn(): void
{
    if (isLoggedIn()) {
        header('Location: profile.php');
        exit;
    }
}

/**
 * Verify that the given blog post belongs to the logged-in user.
 * Returns the blog row if authorized, otherwise redirects.
 */
function requireBlogOwnership(mysqli $conn, int $blogId): array
{
    requireLogin();

    $stmt = $conn->prepare(
        'SELECT b.*, u.username
         FROM blogpost b
         JOIN user u ON b.user_id = u.id
         WHERE b.id = ?'
    );
    $stmt->bind_param('i', $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();

    if (!$blog) {
        header('Location: profile.php');
        exit;
    }

    if ((int) $blog['user_id'] !== (int) $_SESSION['user_id']) {
        header('Location: profile.php');
        exit;
    }

    return $blog;
}

/**
 * Escape output for safe HTML display.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Create a short excerpt from blog content.
 */
function createExcerpt(string $content, int $length = 150): string
{
    $text = strip_tags($content);
    $text = preg_replace('/\s+/', ' ', trim($text));

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return mb_substr($text, 0, $length) . '...';
}

/**
 * Format a date for display.
 */
function formatDate(string $date): string
{
    return date('F j, Y', strtotime($date));
}
