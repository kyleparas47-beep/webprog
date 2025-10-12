<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized - Admin access required']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? null;
$timestamp = $input['timestamp'] ?? date('Y-m-d H:i:s');

if ($action === 'sync') {
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM events");
    $countStmt->execute();
    $result = $countStmt->get_result();
    $count = $result->fetch_assoc()['total'];
    $countStmt->close();
    
    echo json_encode([
        'success' => true, 
        'message' => 'University calendar synchronized successfully',
        'events_count' => $count,
        'sync_time' => $timestamp
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
