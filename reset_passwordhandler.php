<?php
session_start();
require_once 'config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_POST['reset_password'])) {
    $token = sanitizeInput($_POST['token']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];


    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['reset_error'] = 'Both password fields are required!';
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    if (strlen($newPassword) < 6) {
        $_SESSION['reset_error'] = 'Password must be at least 6 characters long!';
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_error'] = 'Passwords do not match!';
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }


    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userId = $result->fetch_assoc()['user_id'];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE student SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        
        if ($updateStmt->execute()) {
            $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();
            $deleteStmt->close();
            
            $_SESSION['login_error'] = 'Password reset successful! Please login with your new password.';
            $_SESSION['active_form'] = 'login';
            header("Location: index.php");
        } else {
            $_SESSION['reset_error'] = 'Failed to update password. Please try again.';
            header("Location: reset_password.php?token=" . urlencode($token));
        }
        $updateStmt->close();
    } else {
        $_SESSION['reset_error'] = 'Invalid or expired token!';
        header("Location: reset_password.php?token=" . urlencode($token));
    }
    $stmt->close();
    exit();
}
?>