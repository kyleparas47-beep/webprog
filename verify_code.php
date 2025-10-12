<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

$error = $_SESSION['verify_error'] ?? '';
$success = $_SESSION['verify_success'] ?? '';
$email = $_SESSION['reset_email'] ?? '';

unset($_SESSION['verify_error'], $_SESSION['verify_success']);

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function showSuccess($message) {
    return !empty($message) ? "<p class='success-message'>$message</p>" : '';
}

// If no email in session, redirect to forgot password
if (empty($email)) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - NUEvents</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .code-input {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .code-input input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
        }
        .code-input input:focus {
            border-color: #1976d2;
        }
        .email-display {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
            color: #666;
        }
        .resend-section {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link {
            color: #1976d2;
            text-decoration: none;
            cursor: pointer;
        }
        .resend-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box active">
            <form action="verify_code_handler.php" method="post" id="verifyForm">
                <div class="logo"></div>
                <h2 class="welcome-text">Enter Verification<br>Code</h2>
                <?= showError($error); ?>
                <?= showSuccess($success); ?>
                
                <div class="email-display">
                    Code sent to: <strong><?= htmlspecialchars($email); ?></strong>
                </div>
                
                <div class="code-input">
                    <input type="text" name="code1" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="code2" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="code3" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="code4" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="code5" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="code6" maxlength="1" pattern="[0-9]" required>
                </div>
                
                <button type="submit" name="verify_code" class="login-button">Verify Code</button>
                
                <div class="resend-section">
                    <p>Didn't receive the code? <a href="#" class="resend-link" onclick="resendCode()">Resend Code</a></p>
                    <p><a href="index.php" class="back-link">Back to Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-focus and move to next input
        const inputs = document.querySelectorAll('.code-input input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                if (e.target.value.length === 1) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
            });
        });

        // Resend code function
        function resendCode() {
            if (confirm('Resend verification code to <?= htmlspecialchars($email); ?>?')) {
                window.location.href = 'resend_code.php';
            }
        }

        // Form submission
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            const code = Array.from(inputs).map(input => input.value).join('');
            if (code.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit code.');
            }
        });
    </script>
</body>
</html>
