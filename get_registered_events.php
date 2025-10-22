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
        e.location,
        COALESCE(er.ticket_number, '') AS ticket_number,
        COALESCE(er.qr_code, '') AS qr_code,
        COALESCE(er.attended, 0) AS attended,
        COALESCE(er.student_name, '') AS student_name,
        COALESCE(er.section, '') AS section,
        COALESCE(er.course, '') AS course,
        DATE_FORMAT(er.attended_at, '%Y-%m-%dT%H:%i:%s') AS attended_at,
        DATE_FORMAT(er.registered_at, '%Y-%m-%dT%H:%i:%s') AS registered_at
    FROM events e 
    INNER JOIN event_registrations er ON e.id = er.event_id 
    WHERE er.student_id = ? 
    ORDER BY e.start_date ASC
");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error, 'error_details' => 'Prepare failed']);
    exit();
}

$stmt->bind_param("i", $student_id);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Query execution error: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode(['success' => true, 'events' => $events]);

$stmt->close();
$conn->close();
?>
