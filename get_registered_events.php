<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        e.id,
        e.title,
        e.description,
        e.event_type,
        DATE_FORMAT(e.start_date, '%Y-%m-%dT%H:%i:%s') AS start_date,
        DATE_FORMAT(e.end_date, '%Y-%m-%dT%H:%i:%s') AS end_date,
        e.location
    FROM events e 
    INNER JOIN event_registrations er ON e.id = er.event_id 
    WHERE er.student_id = ? 
    ORDER BY e.start_date ASC
");
$stmt->bind_param("i", $student_id);
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
