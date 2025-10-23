<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/config.php';

$error = $_SESSION['reset_error'] ?? '';
$success = $_SESSION['reset_success'] ?? '';
$email = $_SESSION['verified_email'] ?? '';
$userId = $_SESSION['verified_user_id'] ?? null;

unset($_SESSION['reset_error'], $_SESSION['reset_success']);

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function showSuccess($message) {
    return !empty($message) ? "<p class='success-message'>$message</p>" : '';
}

// Check if user is verified (came from code verification)
if (empty($userId) || empty($email)) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NUEvents</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-box active">
            <form action="reset_passwordhandler.php" method="post">
                <div class="logo"></div>
                <h2 class="welcome-text">Reset Your<br>Password</h2>
                <?= showError($error); ?>
                <?= showSuccess($success); ?>
                
                <div class="email-display" style="background: #f5f5f5; padding: 10px; border-radius: 5px; margin: 10px 0; text-align: center; color: #666;">
                    Resetting password for: <strong><?= htmlspecialchars($email); ?></strong>
                </div>
                
                <div class="input-container">
                    <input type="password" name="new_password" placeholder="New Password" required minlength="6">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
                </div>
                
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId); ?>">
                
                <button type="submit" name="reset_password" class="login-button">Reset Password</button>
                
                <a href="index.php" class="back-link">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>