<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '',
    'forgot_password' => $_SESSION['forgot_password_error'] ?? ''
];
$success_messages = [
    'forgot_password' => $_SESSION['forgot_password_success'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['forgot_password_error'], $_SESSION['forgot_password_success'], $_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}
function showSuccess($message) {
    return !empty($message) ? "<div class='success-message'>$message</div>" : '';
}
function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NUEvents - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
        <div class="form-box 
        <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <div class="logo"></div>
                <h2 class="welcome-text">Welcome,<br>Nationalian!</h2>
                <?= showError($errors['login']); ?>
                
                <div class="input-container">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <a href="#" class="forgot-password" onclick="showForm('forgot-password-form'); return false;">Forgot Password?</a>
                
                <p class="register-text">Don't have an account? <a href="#" class="register-link" onclick="showForm('register-form'); return false;">Register</a></p>
                
                <button type="submit" name="login" class="login-button">Login</button>
            </form>
        </div>
        <div class="form-box 
        <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <div class="logo"></div>
                <h2 class="welcome-text">Register to<br>NUsync</h2>
                <?= showError($errors['register']); ?>
                
                <div class="input-container">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="">-Select role-</option>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <p class="register-text">Already have an account? <a href="#" class="register-link" onclick="showForm('login-form'); return false;">Login</a></p>
                
                <button type="submit" name="register" class="login-button">Register</button>
            </form>
        </div>
        <div class="form-box 
        <?= isActiveForm('forgot_password', $activeForm); ?>" id="forgot-password-form">
            <form action="forgot_password.php" method="post">
                <div class="logo"></div>
                <h2 class="welcome-text">Reset Your<br>Password</h2>
                <?= showError($errors['forgot_password']); ?>
                <?= showSuccess($success_messages['forgot_password']); ?>
                
                <div class="input-container">
                    <input type="email" name="email" placeholder="Enter your email address" required>
                </div>
                
                <p class="register-text">Remember your password? <a href="#" class="register-link" onclick="showForm('login-form'); return false;">Login</a></p>
                
                <button type="submit" name="forgot_password" class="login-button">Send Reset Link</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>