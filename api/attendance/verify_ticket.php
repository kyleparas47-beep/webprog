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

// Verify ticket and get registration details
$stmt = $conn->prepare("
    SELECT 
        er.*,
        e.title as event_title,
        e.event_type,
        e.location,
        DATE_FORMAT(e.start_date, '%M %d, %Y %h:%i %p') AS event_datetime,
        s.name as student_name_db,
        s.email as student_email,
        COALESCE(er.student_name, s.name) as student_name,
        COALESCE(er.section, 'Not specified') as section,
        COALESCE(er.course, 'Not specified') as course
    FROM event_registrations er
    JOIN events e ON er.event_id = e.id
    JOIN student s ON er.student_id = s.id
    WHERE er.ticket_number = ?
");
$stmt->bind_param("s", $ticket_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ticket number']);
    $stmt->close();
    $conn->close();
    exit();
}

$registration = $result->fetch_assoc();

// Check if already attended
if ($registration['attended'] == 1) {
    echo json_encode([
        'success' => true,
        'already_attended' => true,
        'message' => 'Student has already attended this event',
        'registration' => [
            'student_name' => $registration['student_name'],
            'section' => $registration['section'],
            'course' => $registration['course'],
            'ticket_number' => $registration['ticket_number'],
            'event_title' => $registration['event_title'],
            'event_type' => $registration['event_type'],
            'attended_at' => $registration['attended_at']
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'already_attended' => false,
        'message' => 'Valid ticket - Ready to mark attendance',
        'registration' => [
            'student_name' => $registration['student_name'],
            'section' => $registration['section'],
            'course' => $registration['course'],
            'ticket_number' => $registration['ticket_number'],
            'event_title' => $registration['event_title'],
            'event_type' => $registration['event_type'],
            'location' => $registration['location'],
            'event_datetime' => $registration['event_datetime']
        ]
    ]);
}

$stmt->close();
$conn->close();
?>

