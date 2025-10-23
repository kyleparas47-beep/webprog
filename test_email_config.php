<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4a5bb8;
        }
        .test-section {
            background: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4a5bb8;
        }
        .success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .code {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
        }
        form {
            margin: 20px 0;
        }
        input[type="email"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background: #4a5bb8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #36408b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Configuration Test</h1>
        <p>This page helps you test if email sending is working on your server.</p>

        <?php
        // Test 1: Check if mail() function exists
        echo '<div class="test-section">';
        echo '<h3>Test 1: PHP mail() Function</h3>';
        if (function_exists('mail')) {
            echo '<div class="success">✅ mail() function is available</div>';
        } else {
            echo '<div class="error">❌ mail() function is NOT available</div>';
        }
        echo '</div>';

        // Test 2: Check PHP configuration
        echo '<div class="test-section">';
        echo '<h3>Test 2: PHP Mail Configuration</h3>';
        echo '<p><strong>SMTP Server:</strong> ' . ini_get('SMTP') . '</p>';
        echo '<p><strong>SMTP Port:</strong> ' . ini_get('smtp_port') . '</p>';
        echo '<p><strong>Sendmail From:</strong> ' . ini_get('sendmail_from') . '</p>';
        echo '</div>';

        // Test 3: Try sending a test email
        if (isset($_POST['test_email'])) {
            $test_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            
            if ($test_email) {
                echo '<div class="test-section">';
                echo '<h3>Test 3: Sending Test Email</h3>';
                
                $subject = 'Test Email from NU Laguna System';
                $message = "Hello!\n\nThis is a test email from your NU Laguna Events system.\n\nIf you received this, your email configuration is working!\n\nTime sent: " . date('Y-m-d H:i:s');
                $headers = "From: NU Laguna Events <noreply@nu-laguna.edu.ph>\r\n";
                $headers .= "Reply-To: noreply@nu-laguna.edu.ph\r\n";
                
                if (@mail($test_email, $subject, $message, $headers)) {
                    echo '<div class="success">';
                    echo '✅ Email sent successfully to: ' . htmlspecialchars($test_email);
                    echo '<br><br><strong>Next steps:</strong>';
                    echo '<ol>';
                    echo '<li>Check your inbox</li>';
                    echo '<li>Check your spam folder</li>';
                    echo '<li>Wait a few minutes (sometimes delivery is delayed)</li>';
                    echo '</ol>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '❌ Email sending failed!';
                    echo '<br><br><strong>Possible reasons:</strong>';
                    echo '<ul>';
                    echo '<li>SMTP is not configured on this server</li>';
                    echo '<li>Firewall is blocking email sending</li>';
                    echo '<li>You need to configure hMailServer or Gmail SMTP</li>';
                    echo '</ul>';
                    echo '<br><strong>Solution:</strong> See <code>EMAIL_SETUP_GUIDE.md</code>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="error">Invalid email address!</div>';
            }
        }

        // Test 4: Check error log
        echo '<div class="test-section">';
        echo '<h3>Test 4: Error Log Location</h3>';
        $error_log = ini_get('error_log');
        if ($error_log) {
            echo '<p>Error log file: <code>' . $error_log . '</code></p>';
        } else {
            echo '<p>Default location: <code>C:\\xampp\\php\\logs\\php_error_log</code></p>';
        }
        echo '<div class="info">';
        echo '💡 <strong>Tip:</strong> Verification codes are logged to this file for testing purposes.';
        echo '<br>Look for lines starting with: <code>Password reset code for...</code>';
        echo '</div>';
        echo '</div>';

        // Test 5: Database check
        echo '<div class="test-section">';
        echo '<h3>Test 5: Database Password Resets Table</h3>';
        require_once 'config.php';
        
        $result = $conn->query("SHOW TABLES LIKE 'password_resets'");
        if ($result->num_rows > 0) {
            echo '<div class="success">✅ password_resets table exists</div>';
            
            // Check recent codes
            $recent = $conn->query("SELECT COUNT(*) as count FROM password_resets");
            $count = $recent->fetch_assoc()['count'];
            echo '<p>Total reset codes generated: <strong>' . $count . '</strong></p>';
            
            if ($count > 0) {
                $latest = $conn->query("SELECT token, expires_at, created_at FROM password_resets ORDER BY id DESC LIMIT 1");
                $code = $latest->fetch_assoc();
                echo '<p><strong>Latest code:</strong></p>';
                echo '<div class="code">';
                echo 'Code: ' . $code['token'] . '<br>';
                echo 'Created: ' . $code['created_at'] . '<br>';
                echo 'Expires: ' . $code['expires_at'];
                echo '</div>';
            }
        } else {
            echo '<div class="error">❌ password_resets table does not exist</div>';
        }
        echo '</div>';
        ?>

        <div class="test-section">
            <h3>Send Test Email</h3>
            <form method="POST">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <button type="submit" name="test_email">Send Test Email</button>
            </form>
        </div>

        <div class="info">
            <h3>📖 How to Fix Email Issues:</h3>
            <ol>
                <li><strong>For Testing (XAMPP):</strong> Check the error log file for verification codes</li>
                <li><strong>For Production:</strong> Set up Gmail SMTP (see EMAIL_SETUP_GUIDE.md)</li>
                <li><strong>Quick Setup:</strong> Install hMailServer (see guide)</li>
            </ol>
            <p><a href="EMAIL_SETUP_GUIDE.md" target="_blank">📄 View Full Setup Guide</a></p>
        </div>
    </div>
</body>
</html>


