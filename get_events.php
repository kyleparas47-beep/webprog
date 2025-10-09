<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$stmt = $conn->prepare("SELECT id, title, description, event_type, start_date, end_date, location, created_by, created_at FROM events ORDER BY start_date ASC");
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode(['success' => true, 'events' => $events]);

$stmt->close();
$conn->close();
?>
