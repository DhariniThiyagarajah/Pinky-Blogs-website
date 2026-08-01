<?php
/** Create the comments table when an older database has not been updated yet. */
function ensureCommentsTable(mysqli $conn): void
{
    $conn->query(
        'CREATE TABLE IF NOT EXISTS blogcomment (
            id INT AUTO_INCREMENT PRIMARY KEY,
            blog_id INT NOT NULL,
            user_id INT NOT NULL,
            comment VARCHAR(500) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_comment_blog_created (blog_id, created_at),
            CONSTRAINT fk_comment_blog FOREIGN KEY (blog_id) REFERENCES blogpost(id) ON DELETE CASCADE,
            CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
}

/** Return the session token used to protect comment submissions. */
function commentToken(): string
{
    if (empty($_SESSION['comment_token'])) {
        $_SESSION['comment_token'] = bin2hex(random_bytes(24));
    }

    return $_SESSION['comment_token'];
}
