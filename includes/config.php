<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "student_db";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for better compatibility
$conn->set_charset("utf8mb4");
?>
