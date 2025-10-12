<?php
session_start();
require_once __DIR__ . 'config.php';

$token = $_GET['token'] ?? '';
$error = $_SESSION['reset_error'] ?? '';
$success = $_SESSION['reset_success'] ?? '';

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

$validToken = false;
$userId = null;

if (!empty($token)) {
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $validToken = true;
        $userId = $result->fetch_assoc()['user_id'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NUEvents</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box active">
            <?php if ($validToken): ?>
                <form action="reset_password_handler.php" method="post">
                    <div class="logo"></div>
                    <h2 class="welcome-text">Reset Your<br>Password</h2>
                    <?= showError($error); ?>
                    <?= showSuccess($success); ?>
                    
                    <div class="input-container">
                        <input type="password" name="new_password" placeholder="New Password" required minlength="6">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
                    </div>
                    
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
                    
                    <button type="submit" name="reset_password" class="login-button">Reset Password</button>
                    
                    <a href="index.php" class="back-link">Back to Login</a>
                </form>
            <?php else: ?>
                <div class="logo"></div>
                <h2 class="welcome-text">Invalid or<br>Expired Link</h2>
                <p class="error-message">This password reset link is invalid or has expired. Please request a new one.</p>
                <a href="index.php" class="back-link">Back to Login</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>