<?php
session_start();
require_once 'config.php';

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
    $role = sanitizeInput($_POST['role']);

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['register_error'] = 'All fields are required!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['register_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'Password must be at least 6 characters long!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
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
            $_SESSION['login_error'] = 'Registration successful! Please login.';
            $_SESSION['active_form'] = 'login';
        } else {
            $_SESSION['register_error'] = 'Registration failed. Please try again.';
            $_SESSION['active_form'] = 'register';
        }
        $stmt->close();
    }
    $checkEmail->close();
    
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Email and password are required!';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['login_error'] = 'Please enter a valid email address!';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
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
                header("Location: admin_page.php");
            } else {
                header("Location: student_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password!';
    $_SESSION['active_form'] = 'login';
    $stmt->close();
    
    header("Location: index.php");
    exit();
}
?>