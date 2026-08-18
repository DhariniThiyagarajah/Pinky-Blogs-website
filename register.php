<?php
/**
 * register.php - User registration page
 *
 * Allows new users to create an account with username, email,
 * and password. Passwords are hashed with password_hash().
 */

$pageTitle = 'Register';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

echo '<link rel="stylesheet" href="css/register.css">';

redirectIfLoggedIn();

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = 'Username must be between 3 and 50 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username may only contain letters, numbers, and underscores.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM user WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($existing) {
            $errors[] = 'Username or email is already taken.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                'INSERT INTO user (username, email, password) VALUES (?, ?, ?)'
            );
            $stmt->bind_param('sss', $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $stmt->close();
                header('Location: login.php?registered=1');
                exit;
            }

            $errors[] = 'Registration failed. Please try again.';
            $stmt->close();
        }
    }
}
?>

<div class="inner-layout register-page">
    <div class="inner-main">
        <div class="kawaii-box register-card">
            <div class="box-cap">&#128150; join us</div>

            <div class="page-header" style="text-align: center;">
                <h1>Create an Account</h1>
                <p>Make your own cozy corner, share your stories, and meet other Pinky Blog writers.</p>
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

            <form method="POST" action="register.php" data-validate novalidate>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($username) ?>" data-required autocomplete="username">
                    <span class="field-error">This field is required.</span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($email) ?>" data-required data-type="email" autocomplete="email">
                    <span class="field-error">This field is required.</span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" data-required data-type="password" data-min-length="6" autocomplete="new-password">
                    <span class="field-hint">At least 6 characters</span>
                    <span class="field-error">This field is required.</span>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" data-required data-type="confirm-password" autocomplete="new-password">
                    <span class="field-error">This field is required.</span>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>

                <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>
    <?php
    $countStmt = $conn->prepare('SELECT COUNT(*) as total FROM blogpost');
    $countStmt->execute();
    $totalPosts = (int) $countStmt->get_result()->fetch_assoc()['total'];
    $countStmt->close();
    require_once __DIR__ . '/includes/sidebar.php';
    ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
