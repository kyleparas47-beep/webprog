<?php
session_start();
require_once __DIR__ . '/config.php';


function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateResetToken() {
    return bin2hex(random_bytes(32));
}


function sendResetEmail($email, $token) {
    $resetLink = "http://localhost/reset_password.php?token=" . $token;
    
    $subject = "Password Reset - NUsync";
    $message = "Click the following link to reset your password: " . $resetLink;
    $headers = "From: noreply@nuevents.com";

    return true;
}

if (isset($_POST['forgot_password'])) {
    $email = sanitizeInput($_POST['email']);


    if (empty($email)) {
        $_SESSION['forgot_password_error'] = 'Email address is required!';
        $_SESSION['active_form'] = 'forgot_password';
        header("Location: /index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['forgot_password_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'forgot_password';
        header("Location: /index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
    
        $token = generateResetToken();
        $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $tokenStmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
        $tokenStmt->bind_param("issss", $userId, $token, $expiryTime, $token, $expiryTime);
        
        if ($tokenStmt->execute()) {
            if (sendResetEmail($email, $token)) {
                $_SESSION['forgot_password_success'] = 'Password reset link has been sent to your email address.';
            } else {
                $_SESSION['forgot_password_error'] = 'Failed to send reset email. Please try again.';
            }
        } else {
            $_SESSION['forgot_password_error'] = 'Failed to generate reset token. Please try again.';
        }
        $tokenStmt->close();
    } else {
        $_SESSION['forgot_password_success'] = 'If your email is registered, you will receive a password reset link.';
    }
    
    $stmt->close();
    $_SESSION['active_form'] = 'forgot_password';
    header("Location: /index.php");
    exit();
}
?>