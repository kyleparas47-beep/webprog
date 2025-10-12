<?php
session_start();
require_once __DIR__ . '/config.php';


function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
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

if (isset($_POST['forgot_password'])) {
    $email = sanitizeInput($_POST['email']);


    if (empty($email)) {
        $_SESSION['forgot_password_error'] = 'Email address is required!';
        $_SESSION['active_form'] = 'forgot_password';
        header("Location: index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['forgot_password_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'forgot_password';
        header("Location: index.php");
        exit();
    }

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
                $_SESSION['forgot_password_success'] = 'A 6-digit verification code has been sent to your email address.';
                $_SESSION['reset_email'] = $email;
                $_SESSION['active_form'] = 'verify_code';
                header("Location: verify_code.php");
                exit();
            } else {
                $_SESSION['forgot_password_error'] = 'Failed to send verification code. Please try again.';
            }
        } else {
            $_SESSION['forgot_password_error'] = 'Failed to generate verification code. Please try again.';
        }
        $codeStmt->close();
    } else {
        $_SESSION['forgot_password_success'] = 'If your email is registered, you will receive a verification code.';
    }
    
    $stmt->close();
    $_SESSION['active_form'] = 'forgot_password';
    header("Location: index.php");
    exit();
}
?>