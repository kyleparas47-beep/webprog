<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$ticket_number = $_GET['ticket'] ?? null;

if (!$ticket_number) {
    header("Location: student_calendar.php");
    exit();
}

// Get ticket and event details
$stmt = $conn->prepare("
    SELECT 
        er.*,
        e.title as event_title,
        e.event_type,
        DATE_FORMAT(e.start_date, '%M %d, %Y') AS event_date,
        DATE_FORMAT(e.start_date, '%h:%i %p') AS event_time,
        e.location,
        s.name as student_name_db
    FROM event_registrations er
    JOIN events e ON er.event_id = e.id
    JOIN student s ON er.student_id = s.id
    WHERE er.ticket_number = ? AND er.student_id = ?
");
$stmt->bind_param("si", $ticket_number, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: student_calendar.php");
    exit();
}

$ticket = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket - National University</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="student.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #36408b
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .ticket-container {
            max-width: 420px;
            width: 100%;
            margin: 10px auto;
        }
        
        .ticket-card {
            background: linear-gradient(135deg, #1e5ba8 0%, #2d6bb3 100%);
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .ticket-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 18px;
            border-bottom: 2px dashed rgba(255, 255, 255, 0.3);
            position: relative;
        }
        
        .ticket-header::before,
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            width: 30px;
            height: 30px;
            background:  #36408b;
            border-radius: 50%;
        }
        
        .ticket-header::before {
            left: -15px;
        }
        
        .ticket-header::after {
            right: -15px;
        }
        
        .university-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .logo-circle {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3px;
        }
        
        .university-name {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }
        
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .event-title {
            font-size: 17px;
            font-weight: 700;
            margin: 8px 0 4px 0;
        }
        
        .event-type {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .ticket-body {
            padding: 18px;
        }
        
        .ticket-section {
            margin-bottom: 12px;
        }
        
        .section-label {
            font-size: 9px;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        
        .section-value {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
        }
        
        .ticket-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .qr-section {
            background: white;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            margin: 12px 0;
        }
        
        .qr-code {
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }
        
        .ticket-number-display {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            margin: 12px 0;
        }
        
        .ticket-number-label {
            font-size: 9px;
            opacity: 0.7;
            margin-bottom: 3px;
        }
        
        .ticket-number-value {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        
        .btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 12px;
        }
        
        .btn-primary {
            background: white;
            color: #1e5ba8;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .attendance-status {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }
        
        .attendance-status.attended {
            background: rgba(76, 175, 80, 0.3);
        }
        
        .attendance-status.not-attended {
            background: rgba(255, 152, 0, 0.3);
        }
        
        @media print {
            body {
                background: white;
            }
            
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-card">
            <div class="ticket-header">
                <div class="university-logo">
                    <div class="logo-circle">
                        <img src="assets/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" 
                             alt="NU Logo" 
                             style="width: 32px; height: 32px; object-fit: contain;">
                    </div>
                    <h1 class="university-name">NationalU</h1>
                </div>
                <span class="status-badge">
                    <?php echo ($ticket['attended'] ?? 0) ? '✓ ATTENDED' : 'PRE-REGISTERED'; ?>
                </span>
                <h2 class="event-title"><?php echo htmlspecialchars($ticket['event_title']); ?></h2>
                <p class="event-type"><?php echo htmlspecialchars($ticket['event_type']); ?></p>
            </div>
            
            <div class="ticket-body">
                <div class="ticket-section">
                    <div class="section-label">ADDRESS</div>
                    <div class="section-value">National University Laguna, KM 53 Pan-Philippine Hwy, Calamba, 4027 Laguna, Philippines</div>
                </div>
                
                <div class="ticket-grid">
                    <div class="ticket-section">
                        <div class="section-label">STUDENT</div>
                        <div class="section-value"><?php echo htmlspecialchars($ticket['student_name']); ?></div>
                    </div>
                    <div class="ticket-section">
                        <div class="section-label">SECTION</div>
                        <div class="section-value"><?php echo htmlspecialchars($ticket['section']); ?></div>
                    </div>
                </div>
                
                <div class="ticket-section">
                    <div class="section-label">COURSE</div>
                    <div class="section-value"><?php echo htmlspecialchars($ticket['course']); ?></div>
                </div>
                
                <div class="ticket-section">
                    <div class="section-label">VENUE</div>
                    <div class="section-value"><?php echo htmlspecialchars($ticket['location'] ?: 'To be announced'); ?></div>
                </div>
                
                <div class="ticket-section">
                    <div class="section-label">EVENT DATE & TIME</div>
                    <div class="section-value"><?php echo $ticket['event_date']; ?> at <?php echo $ticket['event_time']; ?></div>
                </div>
                
                <div class="qr-section">
                    <div class="qr-code" id="qrcode"></div>
                </div>
                
                <div class="ticket-number-display">
                    <div class="ticket-number-label">Ticket No.</div>
                    <div class="ticket-number-value"><?php echo htmlspecialchars($ticket['ticket_number']); ?></div>
                </div>
                
                <?php if ($ticket['attended'] ?? 0): ?>
                <div class="attendance-status attended">
                    <i class="fas fa-check-circle"></i> Attended on <?php echo date('M d, Y', strtotime($ticket['attended_at'])); ?>
                </div>
                <?php else: ?>
                <div class="attendance-status not-attended">
                    <i class="fas fa-clock"></i> Present this ticket at the event venue
                </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Ticket
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='student_calendar.php'">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo htmlspecialchars($ticket['qr_code']); ?>",
            width: 150,
            height: 150,
            colorDark: "#1e5ba8",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>

