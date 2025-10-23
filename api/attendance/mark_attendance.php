<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$ticket_number = trim($_POST['ticket_number'] ?? '');

if (empty($ticket_number)) {
    echo json_encode(['success' => false, 'message' => 'Ticket number is required']);
    exit();
}

// Check if ticket exists and not already attended
$checkStmt = $conn->prepare("SELECT id, attended FROM event_registrations WHERE ticket_number = ?");
$checkStmt->bind_param("s", $ticket_number);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ticket number']);
    $checkStmt->close();
    $conn->close();
    exit();
}

$registration = $result->fetch_assoc();

if ($registration['attended'] == 1) {
    echo json_encode(['success' => false, 'message' => 'Attendance already marked for this ticket']);
    $checkStmt->close();
    $conn->close();
    exit();
}

// Mark attendance
$stmt = $conn->prepare("UPDATE event_registrations SET attended = 1, attended_at = NOW() WHERE ticket_number = ?");
$stmt->bind_param("s", $ticket_number);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Attendance marked successfully',
        'attended_at' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark attendance']);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>

