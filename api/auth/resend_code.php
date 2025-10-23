<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateResetCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendResetCode($email, $code) {
    // Store in session for backup
    $_SESSION['reset_code'] = $code;
    $_SESSION['reset_email'] = $email;
    
    // Try to send actual email
    $subject = 'Password Reset Verification Code - NU Laguna';
    
    $message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background: linear-gradient(135deg, #4a5bb8 0%, #36408b 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; }
        .code-box { background: #f0f0f0; border: 2px dashed #4a5bb8; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
        .code { font-size: 36px; font-weight: bold; color: #4a5bb8; letter-spacing: 8px; font-family: monospace; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔒 Password Reset Request</h1>
        </div>
        <div class='content'>
            <h2>Hello!</h2>
            <p>You have requested a new verification code to reset your password.</p>
            <p>Please use the following verification code:</p>
            <div class='code-box'>
                <div class='code'>$code</div>
            </div>
            <div class='warning'>
                <strong>⏰ Important:</strong> This code will expire in <strong>15 minutes</strong>.
            </div>
            <p>If you did not request a password reset, please ignore this email.</p>
            <p>Best regards,<br><strong>National University Laguna</strong></p>
        </div>
        <div class='footer'>
            <p>This is an automated email. Please do not reply.</p>
            <p>&copy; " . date('Y') . " National University Laguna. All rights reserved.</p>
        </div>
    </div>
</body>
</html>";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: NU Laguna Events <noreply@nu-laguna.edu.ph>" . "\r\n";
    $headers .= "Reply-To: noreply@nu-laguna.edu.ph" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Attempt to send email
    $emailSent = @mail($email, $subject, $message, $headers);
    
    // Log for debugging
    error_log("Resend password reset code for $email: $code - Email sent: " . ($emailSent ? 'Yes' : 'No'));
    
    return true;
}

$email = $_SESSION['reset_email'] ?? '';

if (empty($email)) {
    header("Location: ../../index.php");
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
