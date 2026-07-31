<?php
$pageTitle = 'Edit Profile';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = (int) $_SESSION['user_id'];
$errors = [];

if (empty($_SESSION['profile_csrf'])) {
    $_SESSION['profile_csrf'] = bin2hex(random_bytes(32));
}

$stmt = $conn->prepare('SELECT username, email, profile_image, cover_image, description, discord, youtube, x_link, spotify FROM user WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

function saveProfileImage(string $field, string $type, array &$errors): ?string
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' could not be uploaded.';
        return null;
    }
    if ($_FILES[$field]['size'] > 5 * 1024 * 1024) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' must be smaller than 5 MB.';
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$field]['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($extensions[$mime])) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' must be a JPG, PNG, WEBP or GIF image.';
        return null;
    }

    $filename = sprintf('user-%d-%s-%s.%s', (int) $_SESSION['user_id'], $type, bin2hex(random_bytes(8)), $extensions[$mime]);
    $destination = __DIR__ . '/assests/images/' . $filename;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        $errors[] = 'The image could not be saved. Please try again.';
        return null;
    }
    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['profile_csrf'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired. Please submit the form again.';
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $discord = trim($_POST['discord'] ?? '');
    $youtube = trim($_POST['youtube'] ?? '');
    $xLink = trim($_POST['x_link'] ?? '');
    $spotify = trim($_POST['spotify'] ?? '');

    if ($username === '' || mb_strlen($username) > 50) $errors[] = 'Username is required and must be 50 characters or fewer.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) $errors[] = 'Enter a valid email address.';
    if (mb_strlen($description) > 600) $errors[] = 'Bio must be 600 characters or fewer.';

    foreach (['YouTube' => $youtube, 'X / Pinterest' => $xLink, 'Spotify' => $spotify, 'Discord' => $discord] as $label => $url) {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if ($url !== '' && (!filter_var($url, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true))) {
            $errors[] = $label . ' must be a complete web address, including https://.';
        }
    }

    $check = $conn->prepare('SELECT id FROM user WHERE (username = ? OR email = ?) AND id <> ? LIMIT 1');
    $check->bind_param('ssi', $username, $email, $userId);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) $errors[] = 'That username or email is already being used.';
    $check->close();

    $profileImage = null;
    $coverImage = null;
    if (!$errors) {
        $profileImage = saveProfileImage('profile_image', 'profile', $errors);
        $coverImage = saveProfileImage('cover_image', 'cover', $errors);
    }

    if (!$errors) {
        $profileValue = $profileImage ?: $user['profile_image'];
        $coverValue = $coverImage ?: $user['cover_image'];
        $stmt = $conn->prepare('UPDATE user SET username = ?, email = ?, description = ?, discord = ?, youtube = ?, x_link = ?, spotify = ?, profile_image = ?, cover_image = ? WHERE id = ?');
        $stmt->bind_param('sssssssssi', $username, $email, $description, $discord, $youtube, $xLink, $spotify, $profileValue, $coverValue, $userId);
        $stmt->execute();
        $stmt->close();
        $_SESSION['username'] = $username;
        header('Location: profile.php?profile_updated=1');
        exit;
    }

    $user = array_merge($user, compact('username', 'email', 'description', 'discord', 'youtube', 'spotify'), ['x_link' => $xLink]);
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="profile-editor-shell">
    <section class="kawaii-box profile-editor">
        <div class="box-cap">♡ edit my profile</div>
        <div class="editor-heading">
            <div><h1>Make it yours</h1><p>Change your details and upload your own profile and cover images.</p></div>
            <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
        </div>

        <?php if ($errors): ?>
            <div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="profile-edit-form">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['profile_csrf']) ?>">
            <div class="form-row"><div class="form-group"><label for="username">Username</label><input id="username" name="username" maxlength="50" required value="<?= e($user['username']) ?>"></div><div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" maxlength="100" required value="<?= e($user['email']) ?>"></div></div>
            <div class="form-group"><label for="description">About me</label><textarea id="description" name="description" maxlength="600" rows="5" placeholder="Write a little about yourself..."><?= e($user['description'] ?? '') ?></textarea><small>Up to 600 characters.</small></div>
            <div class="image-upload-grid"><div class="form-group"><label for="profile_image">Profile photo</label><input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/webp,image/gif"><small>Square images work best. Maximum 5 MB.</small></div><div class="form-group"><label for="cover_image">Cover image</label><input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp,image/gif"><small>Wide images work best. Maximum 5 MB.</small></div></div>
            <fieldset><legend>Social links</legend><div class="form-row"><div class="form-group"><label for="youtube">YouTube URL</label><input type="url" id="youtube" name="youtube" placeholder="https://..." value="<?= e($user['youtube'] ?? '') ?>"></div><div class="form-group"><label for="x_link">X / Pinterest URL</label><input type="url" id="x_link" name="x_link" placeholder="https://..." value="<?= e($user['x_link'] ?? '') ?>"></div></div><div class="form-row"><div class="form-group"><label for="discord">Discord URL</label><input type="url" id="discord" name="discord" placeholder="https://..." value="<?= e($user['discord'] ?? '') ?>"></div><div class="form-group"><label for="spotify">Spotify URL</label><input type="url" id="spotify" name="spotify" placeholder="https://..." value="<?= e($user['spotify'] ?? '') ?>"></div></div></fieldset>
            <button type="submit" class="btn btn-primary">Save Profile</button>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
