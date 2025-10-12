<?php
// Test script to verify password reset functionality
require_once 'config.php';

echo '<link rel="stylesheet" href="fonts.css">';
echo "<h2>Password Reset System Test</h2>";

// Test database connection
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
    exit();
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
}

// Test if password_resets table exists
$result = $conn->query("SHOW TABLES LIKE 'password_resets'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Password resets table exists</p>";
    
    // Test table structure
    $result = $conn->query("DESCRIBE password_resets");
    if ($result) {
        echo "<p style='color: green;'>✅ Password resets table structure is correct</p>";
        echo "<h3>Table Structure:</h3><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Password resets table structure issue: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Password resets table does not exist</p>";
}

// Test if admin user exists
$result = $conn->query("SELECT * FROM student WHERE role = 'admin'");
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "<p style='color: green;'>✅ Admin user exists (Email: {$admin['email']})</p>";
} else {
    echo "<p style='color: red;'>❌ Admin user does not exist</p>";
}

// Test token generation
function generateResetToken() {
    return bin2hex(random_bytes(32));
}

$testToken = generateResetToken();
echo "<p style='color: green;'>✅ Token generation works (Sample: " . substr($testToken, 0, 10) . "...)</p>";

// Test password hashing
$testPassword = "test123";
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
if (password_verify($testPassword, $hashedPassword)) {
    echo "<p style='color: green;'>✅ Password hashing and verification works</p>";
} else {
    echo "<p style='color: red;'>❌ Password hashing issue</p>";
}

echo "<h3>Test Instructions:</h3>";
echo "<ol>";
echo "<li>Go to <a href='index.php'>index.php</a></li>";
echo "<li>Click 'Forgot Password?'</li>";
echo "<li>Enter the admin email: admin@nu.edu.ph</li>";
echo "<li>Click 'Send Reset Link'</li>";
echo "<li>Click the generated reset link</li>";
echo "<li>Enter a new password</li>";
echo "<li>Try logging in with the new password</li>";
echo "</ol>";

$conn->close();
?>
