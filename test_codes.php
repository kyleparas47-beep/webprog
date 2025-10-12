<?php
// Test page to show generated reset codes for development
session_start();
require_once 'config.php';

echo "<h2>Password Reset Codes - Development Testing</h2>";

// Show current session codes
if (isset($_SESSION['reset_code']) && isset($_SESSION['reset_email'])) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>Current Reset Code:</h3>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($_SESSION['reset_email']) . "</p>";
    echo "<p><strong>Code:</strong> <span style='font-size: 24px; font-weight: bold; color: #1976d2;'>" . htmlspecialchars($_SESSION['reset_code']) . "</span></p>";
    echo "</div>";
} else {
    echo "<p style='color: #666;'>No active reset codes in session.</p>";
}

// Show codes from database
echo "<h3>Active Codes in Database:</h3>";
$stmt = $conn->prepare("
    SELECT pr.token, pr.expires_at, s.email, s.name 
    FROM password_resets pr 
    JOIN student s ON pr.user_id = s.id 
    WHERE pr.expires_at > NOW() 
    ORDER BY pr.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th style='padding: 10px;'>Email</th>";
    echo "<th style='padding: 10px;'>Name</th>";
    echo "<th style='padding: 10px;'>Code</th>";
    echo "<th style='padding: 10px;'>Expires At</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='padding: 10px; font-size: 18px; font-weight: bold; color: #1976d2;'>" . htmlspecialchars($row['token']) . "</td>";
        echo "<td style='padding: 10px;'>" . htmlspecialchars($row['expires_at']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: #666;'>No active codes in database.</p>";
}

$stmt->close();

echo "<h3>Test Instructions:</h3>";
echo "<ol>";
echo "<li>Go to <a href='index.php'>index.php</a></li>";
echo "<li>Click 'Forgot Password?'</li>";
echo "<li>Enter email: <strong>admin@nu.edu.ph</strong></li>";
echo "<li>Click 'Send Reset Link'</li>";
echo "<li>You'll be redirected to the code verification page</li>";
echo "<li>Use the code shown above or in this test page</li>";
echo "<li>Enter the 6-digit code</li>";
echo "<li>Set your new password</li>";
echo "</ol>";

echo "<p><a href='index.php'>← Back to Login</a></p>";

$conn->close();
?>
