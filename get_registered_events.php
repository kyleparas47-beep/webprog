<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT e.* 
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
