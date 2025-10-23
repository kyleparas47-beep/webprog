<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$ticket_number = $_GET['ticket'] ?? '';
$student_id = $_SESSION['user_id'];

if (empty($ticket_number)) {
    echo json_encode(['success' => false, 'message' => 'Ticket number required']);
    exit();
}

$stmt = $conn->prepare("
    SELECT student_name, section, course, ticket_number
    FROM event_registrations
    WHERE ticket_number = ? AND student_id = ?
");
$stmt->bind_param("si", $ticket_number, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Registration not found']);
    $stmt->close();
    $conn->close();
    exit();
}

$registration = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'student_name' => $registration['student_name'],
    'section' => $registration['section'],
    'course' => $registration['course'],
    'ticket_number' => $registration['ticket_number']
]);

$stmt->close();
$conn->close();
?>

