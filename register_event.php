<?php
session_start();
require_once __DIR__ . 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'] ?? null;

if (!$event_id) {
    echo json_encode(['success' => false, 'message' => 'Event ID required']);
    exit();
}

$checkStmt = $conn->prepare("SELECT id FROM event_registrations WHERE student_id = ? AND event_id = ?");
$checkStmt->bind_param("ii", $student_id, $event_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Already registered']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO event_registrations (student_id, event_id, registered_at) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $student_id, $event_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Successfully registered']);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
