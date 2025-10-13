<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$stmt = $conn->prepare(
    "SELECT 
        id, 
        title, 
        description, 
        event_type, 
        DATE_FORMAT(start_date, '%Y-%m-%dT%H:%i:%s') AS start_date, 
        DATE_FORMAT(end_date, '%Y-%m-%dT%H:%i:%s') AS end_date, 
        location, 
        capacity,
        created_by, 
        DATE_FORMAT(created_at, '%Y-%m-%dT%H:%i:%s') AS created_at 
     FROM events 
     ORDER BY start_date ASC"
);
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
