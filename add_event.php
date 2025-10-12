<?php
session_start();
require_once __DIR__ . '/config.php';

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
    
    // Normalize and validate date/time inputs from datetime-local (e.g., 2025-10-13T09:00)
    $normalize_datetime = function ($value) {
        $value = trim((string)$value);
        if ($value === '') return '';
        // Replace 'T' with space for MySQL DATETIME
        $value = str_replace('T', ' ', $value);
        // Append seconds if missing
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $value)) {
            $value .= ':00';
        }
        return $value;
    };

    $start_date = $normalize_datetime($start_date);
    $end_date = $normalize_datetime($end_date);

    // Basic chronological validation
    try {
        $start_dt = new DateTime($start_date);
        $end_dt = new DateTime($end_date);
        if ($end_dt <= $start_dt) {
            echo json_encode(['success' => false, 'message' => 'End date/time must be after start date/time']);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid date/time format']);
        exit();
    }

    // Ensure event_type is within allowed values
    $allowed_types = ['Events', 'Webinar', 'Seminars', 'Workshop'];
    if (!in_array($event_type, $allowed_types, true)) {
        $event_type = 'Events';
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
