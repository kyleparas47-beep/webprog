<?php
// Test script to verify database connection and basic functionality
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Test database connection
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
    exit();
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
}

// Test if tables exist
$tables = ['student', 'events', 'event_registrations'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Table '$table' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Table '$table' does not exist</p>";
    }
}

// Test if admin user exists
$result = $conn->query("SELECT * FROM student WHERE role = 'admin'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Admin user exists</p>";
} else {
    echo "<p style='color: red;'>❌ Admin user does not exist</p>";
}

// Test events table structure
$result = $conn->query("DESCRIBE events");
if ($result) {
    echo "<p style='color: green;'>✅ Events table structure is correct</p>";
} else {
    echo "<p style='color: red;'>❌ Events table structure issue: " . $conn->error . "</p>";
}

$conn->close();
?>
