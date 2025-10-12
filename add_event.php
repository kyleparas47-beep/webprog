<?php
session_start();
require_once __DIR__ . 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_type = trim($_POST['event_type']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = trim($_POST['location']);
    $created_by = $_SESSION['user_id'];
    
    if (empty($title) || empty($event_type) || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_type, start_date, end_date, location, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssi", $title, $description, $event_type, $start_date, $end_date, $location, $created_by);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event created successfully', 'event_id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create event: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
