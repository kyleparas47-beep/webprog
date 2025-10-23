<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_POST['verify_code'])) {
    $code1 = sanitizeInput($_POST['code1'] ?? '');
    $code2 = sanitizeInput($_POST['code2'] ?? '');
    $code3 = sanitizeInput($_POST['code3'] ?? '');
    $code4 = sanitizeInput($_POST['code4'] ?? '');
    $code5 = sanitizeInput($_POST['code5'] ?? '');
    $code6 = sanitizeInput($_POST['code6'] ?? '');
    
    $enteredCode = $code1 . $code2 . $code3 . $code4 . $code5 . $code6;
    $email = $_SESSION['reset_email'] ?? '';

    if (empty($enteredCode) || strlen($enteredCode) !== 6) {
        $_SESSION['verify_error'] = 'Please enter the complete 6-digit code!';
        header("Location: verify_code.php");
        exit();
    }

    if (empty($email)) {
        $_SESSION['verify_error'] = 'Session expired. Please request a new code.';
        header("Location: ../../index.php");
        exit();
    }

    // Get user ID from email
    $stmt = $conn->prepare("SELECT id FROM student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['verify_error'] = 'Invalid email address.';
        header("Location: ../../index.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();

    // Verify the code
    $codeStmt = $conn->prepare("SELECT id FROM password_resets WHERE user_id = ? AND token = ? AND expires_at > NOW()");
    $codeStmt->bind_param("is", $userId, $enteredCode);
    $codeStmt->execute();
    $codeResult = $codeStmt->get_result();

    if ($codeResult->num_rows > 0) {
        // Code is valid, redirect to reset password page
        $_SESSION['verified_user_id'] = $userId;
        $_SESSION['verified_email'] = $email;
        $_SESSION['verify_success'] = 'Code verified successfully!';
        header("Location: reset_password.php");
        exit();
    } else {
        $_SESSION['verify_error'] = 'Invalid or expired code. Please try again.';
        header("Location: verify_code.php");
        exit();
    }

    $codeStmt->close();
} else {
    header("Location: ../../index.php");
    exit();
}
?>
