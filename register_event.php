<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'] ?? null;
$student_name = trim($_POST['student_name'] ?? '');
$section = trim($_POST['section'] ?? '');
$course = trim($_POST['course'] ?? '');

if (!$event_id || !$student_name || !$section || !$course) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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

// Generate unique ticket number
function generateTicketNumber($conn) {
    do {
        $ticket_number = 'NU-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $checkTicket = $conn->prepare("SELECT id FROM event_registrations WHERE ticket_number = ?");
        $checkTicket->bind_param("s", $ticket_number);
        $checkTicket->execute();
        $ticketResult = $checkTicket->get_result();
        $checkTicket->close();
    } while ($ticketResult->num_rows > 0);
    
    return $ticket_number;
}

$ticket_number = generateTicketNumber($conn);

// Generate QR code data (we'll store the ticket number as QR data)
$qr_code_data = $ticket_number;

$stmt = $conn->prepare("INSERT INTO event_registrations (student_id, event_id, student_name, section, course, ticket_number, qr_code, registered_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("iisssss", $student_id, $event_id, $student_name, $section, $course, $ticket_number, $qr_code_data);

if ($stmt->execute()) {
    $registration_id = $conn->insert_id;
    echo json_encode([
        'success' => true, 
        'message' => 'Successfully registered',
        'ticket_number' => $ticket_number,
        'registration_id' => $registration_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
