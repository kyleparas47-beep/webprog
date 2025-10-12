<?php
session_start();
require_once __DIR__ . '/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateResetCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendResetCode($email, $code) {
    // For development/testing purposes, store the code in session
    // In production, you would use a proper email service like PHPMailer
    $_SESSION['reset_code'] = $code;
    $_SESSION['reset_email'] = $email;
    
    // Log the code for testing (in production, this would be sent via email)
    error_log("Password reset code for $email: $code");
    
    return true;
}

$email = $_SESSION['reset_email'] ?? '';

if (empty($email)) {
    header("Location: index.php");
    exit();
}

// Get user ID from email
$stmt = $conn->prepare("SELECT id FROM student WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];

    $code = generateResetCode();
    $expiryTime = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Code expires in 15 minutes

    $codeStmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
    $codeStmt->bind_param("issss", $userId, $code, $expiryTime, $code, $expiryTime);
    
    if ($codeStmt->execute()) {
        if (sendResetCode($email, $code)) {
            $_SESSION['verify_success'] = 'A new verification code has been sent to your email address.';
        } else {
            $_SESSION['verify_error'] = 'Failed to send verification code. Please try again.';
        }
    } else {
        $_SESSION['verify_error'] = 'Failed to generate verification code. Please try again.';
    }
    $codeStmt->close();
} else {
    $_SESSION['verify_error'] = 'Invalid email address.';
}

$stmt->close();
header("Location: verify_code.php");
exit();
?>
