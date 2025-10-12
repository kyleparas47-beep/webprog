<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_type = trim($_POST['event_type']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = trim($_POST['location']);
    
    if (empty($event_id) || empty($title) || empty($event_type) || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_type = ?, start_date = ?, end_date = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $event_type, $start_date, $end_date, $location, $event_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update event: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
