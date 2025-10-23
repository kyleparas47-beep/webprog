<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../includes/config.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
$user_role = $_SESSION['role'] ?? '';

// Handle form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    
    // Validation
    if (empty($new_name)) {
        $message = 'Name cannot be empty.';
        $message_type = 'error';
    } elseif (empty($new_email)) {
        $message = 'Email cannot be empty.';
        $message_type = 'error';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $message_type = 'error';
    } else {
        // Check if email is already taken by another user
        $check_email = $conn->prepare("SELECT id FROM student WHERE email = ? AND id != ?");
        if (!$check_email) {
            $message = 'Database error: ' . $conn->error;
            $message_type = 'error';
        } else {
            $check_email->bind_param("si", $new_email, $user_id);
            $check_email->execute();
            $result = $check_email->get_result();
            
            if ($result->num_rows > 0) {
                $message = 'This email is already taken by another user.';
                $message_type = 'error';
            } else {
                // Update user information
                $update_stmt = $conn->prepare("UPDATE student SET name = ?, email = ? WHERE id = ?");
                if (!$update_stmt) {
                    $message = 'Database error: ' . $conn->error;
                    $message_type = 'error';
                } else {
                    $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);
                    
                    if ($update_stmt->execute()) {
                        // Update session data
                        $_SESSION['name'] = $new_name;
                        $_SESSION['email'] = $new_email;
                        
                        // Update local variables for display
                        $user_name = $new_name;
                        $user_email = $new_email;
                        
                        $message = 'Profile updated successfully!';
                        $message_type = 'success';
                    } else {
                        $message = 'Failed to update profile. Please try again.';
                        $message_type = 'error';
                    }
                    $update_stmt->close();
                }
            }
            $check_email->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - National University</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #36408b;
            color: #31344d;
            margin: 0;
            padding-top: 100px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-container {
            background-color: #d8dce7;
            border-radius: 12px;
            width: 400px;
            padding: 2rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: #454995;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .back-btn {
            background: transparent;
            border: none;
            font-size: 1.4rem;
            color: #454995;
            cursor: pointer;
            user-select: none;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.8rem;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #37477b;
            object-fit: cover;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #454995;
        }

        .email {
            font-size: 1rem;
            color: #7a7c90;
            font-weight: 600;
            margin-top: 0.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #37477b;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            height: 50px;
            background-color: white;
            border: 2px solid #e5e9fb;
            border-radius: 10px;
            padding: 0 1rem;
            font-size: 1rem;
            color: #37477b;
            font-weight: 600;
            box-shadow: 3px 3px 10px #bebfe0, -3px -3px 10px #ffffff;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #37477b;
            box-shadow: 3px 3px 10px #bebfe0, -3px -3px 10px #ffffff, 0 0 0 3px rgba(55, 71, 123, 0.1);
        }

        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-button {
            height: 50px;
            background-color: white;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            color: #37477b;
            cursor: pointer;
            box-shadow: 3px 3px 10px #bebfe0, -3px -3px 10px #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-button:hover {
            background-color: #d3d6ed;
        }
    </style>
</head>
<body onload="initPage()">
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="../../assets/images/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" alt="National University Logo" class="logo-img">
                </div>
                <span class="logo-text">National University</span>
            </div>
            <nav class="nav-links">
                <a href="dashboard.php" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="calendar.php" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="../shared/view_events.php" class="nav-link">VIEW EVENTS</a>
                <span class="nav-divider">|</span>
                <a href="attendance.php" class="nav-link">ATTENDANCE</a>
            </nav>
            <div class="header-icons">
                <button onclick="showProfileMenu()" class="icon-btn user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <button class="icon-btn hamburger-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    
    <div class="profile-container">
        <header class="profile-header">
            <button class="back-btn" onclick="goBack()">&larr;</button>
            <h2>Profile Information</h2>
        </header>
        
        <div class="profile-info">
            <img class="avatar" src="https://ui-avatars.com/api/?name=<?= urlencode($user_name) ?>&background=1976d2&color=fff&size=90" alt="Avatar of <?= htmlspecialchars($user_name) ?>" />
            <div class="user-details">
                <div class="name"><?= htmlspecialchars($user_name) ?></div>
                <div class="email"><?= htmlspecialchars($user_email) ?></div>
                <div class="role" style="font-size: 12px; color: #7a7c90; margin-top: 5px; text-transform: uppercase; font-weight: 600;"><?= htmlspecialchars($user_role) ?></div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="update_profile" value="1">
            
            <div class="form-group">
                <label class="form-label" for="name">Full Name:</label>
                <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($user_name) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address:</label>
                <input type="email" id="email" name="email" class="form-input" value="<?= htmlspecialchars($user_email) ?>" required>
            </div>
            
            <div class="action-buttons">
                <button type="submit" class="action-button">Save Changes</button>
                <button type="button" class="action-button" onclick="goBack()">Go Back</button>
            </div>
        </form>
    </div>

    <script>
        function initPage() {
            // Animation starts automatically via CSS
        }

        function goBack() {
            window.location.href = 'dashboard.php';
        }

        // Auto-hide success messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.message.success');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.remove();
                }, 3000);
            }
        });
    </script>
</body>
</html>
