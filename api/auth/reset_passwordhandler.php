<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_POST['reset_password'])) {
    $userId = intval($_POST['user_id']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['reset_error'] = 'Both password fields are required!';
        header("Location: reset_password.php");
        exit();
    }

    if (strlen($newPassword) < 6) {
        $_SESSION['reset_error'] = 'Password must be at least 6 characters long!';
        header("Location: reset_password.php");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_error'] = 'Passwords do not match!';
        header("Location: reset_password.php");
        exit();
    }

    // Verify user is still in session
    if ($userId !== $_SESSION['verified_user_id']) {
        $_SESSION['reset_error'] = 'Session expired. Please start over.';
        header("Location: ../../index.php");
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare("UPDATE student SET password = ? WHERE id = ?");
    $updateStmt->bind_param("si", $hashedPassword, $userId);
    
    if ($updateStmt->execute()) {
        // Delete the reset code from database
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();
        $deleteStmt->close();
        
        // Clear session variables
        unset($_SESSION['verified_user_id'], $_SESSION['verified_email'], $_SESSION['reset_email'], $_SESSION['reset_code']);
        
        $_SESSION['login_error'] = 'Password reset successful! Please login with your new password.';
        $_SESSION['active_form'] = 'login';
        header("Location: ../../index.php");
    } else {
        $_SESSION['reset_error'] = 'Failed to update password. Please try again.';
        header("Location: reset_password.php");
    }
    $updateStmt->close();
    exit();
}
?>