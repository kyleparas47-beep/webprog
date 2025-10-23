<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if(isset($_POST['register'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = 'All fields are required!';
        $_SESSION['active_form'] = 'register';
        header("Location: ../../index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['register_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'register';
        header("Location: ../../index.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'Password must be at least 6 characters long!';
        $_SESSION['active_form'] = 'register';
        header("Location: ../../index.php");
        exit();
    }

    // Automatically assign role based on email domain
    $emailLower = strtolower($email);
    $role = '';
    
    // Check for student email domain
    if (strpos($emailLower, '@students.nu-laguna.edu.ph') !== false) {
        $role = 'student';
    }
    // Check for admin email domain
    else if (strpos($emailLower, '@nu-laguna.edu.ph') !== false) {
        $role = 'admin';
    }
    // Reject non-NU emails
    else {
        $_SESSION['register_error'] = 'Please use an official NU Laguna email address!';
        $_SESSION['active_form'] = 'register';
        header("Location: ../../index.php");
        exit();
    }

    $checkEmail = $conn->prepare("SELECT email FROM student WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO student (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        
        if ($stmt->execute()) {
            $_SESSION['register_success'] = 'Registration successful! Please login.';
            $_SESSION['active_form'] = 'login';
        } else {
            $_SESSION['register_error'] = 'Registration failed. Please try again.';
            $_SESSION['active_form'] = 'register';
        }
        $stmt->close();
    }
    $checkEmail->close();
    
    header("Location: ../../index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Email and password are required!';
        $_SESSION['active_form'] = 'login';
        header("Location: ../../index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['login_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'login';
        header("Location: ../../index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password!';
    $_SESSION['active_form'] = 'login';
    $stmt->close();
    
    header("Location: ../../index.php");
    exit();
}
?>