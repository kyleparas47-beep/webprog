<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../includes/config.php';

$user_role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'];

$success_message = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $success_message = 'Event deleted successfully!';
}
$upcoming_events = [];
$upcoming_query = "SELECT * FROM events WHERE DATE(start_date) >= CURDATE() ORDER BY start_date ASC";
$upcoming_result = $conn->query($upcoming_query);
if ($upcoming_result) {
    while ($row = $upcoming_result->fetch_assoc()) {
        $upcoming_events[] = $row;
    }
}

$previous_events = [];
$previous_query = "SELECT * FROM events WHERE DATE(start_date) < CURDATE() ORDER BY start_date DESC";
$previous_result = $conn->query($previous_query);
if ($previous_result) {
    while ($row = $previous_result->fetch_assoc()) {
        $previous_events[] = $row;
    }
}

$registered_events = [];
$registered_tickets = [];
if ($user_role === 'student') {
    $reg_query = "SELECT event_id, ticket_number FROM event_registrations WHERE student_id = ?";
    $reg_stmt = $conn->prepare($reg_query);
    $reg_stmt->bind_param("i", $user_id);
    $reg_stmt->execute();
    $reg_result = $reg_stmt->get_result();
    while ($row = $reg_result->fetch_assoc()) {
        $registered_events[] = $row['event_id'];
        $registered_tickets[$row['event_id']] = $row['ticket_number'];
    }
    $reg_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events - National University</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');
        
        *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #36408b;
            color: #31344d;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px;
        }

        .events-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .events-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .events-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .events-section {
            margin-bottom: 50px;
        }

        .section-title {
            background: linear-gradient(135deg, #dde0f0 0%, #e8eaf6 100%);
            color: #37477b;
            padding: 15px 25px;
            border-radius: 15px 15px 0 0;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .events-table-container {
            background: linear-gradient(135deg, #dde0f0 0%, #e8eaf6 100%);
            border-radius: 0 0 15px 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow-x: auto;
        }

        .events-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .events-table th {
            background: linear-gradient(135deg, #37477b 0%, #2c3050 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .events-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e9fb;
            vertical-align: middle;
        }

        .events-table tr:last-child td {
            border-bottom: none;
        }

        .events-table tr:hover {
            background-color: #f8f9ff;
        }

        .event-title {
            font-weight: 700;
            color: #37477b;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .event-description {
            color: #7a7c90;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .event-date {
            font-weight: 600;
            color: #37477b;
            font-size: 0.9rem;
        }

        .event-time {
            color: #7a7c90;
            font-size: 0.85rem;
        }

        .event-location {
            color: #7a7c90;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .event-capacity {
            text-align: center;
            font-weight: 600;
            color: #37477b;
        }

        .event-status {
            text-align: center;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-upcoming {
            background-color: #d4edda;
            color: #155724;
        }

        .status-registered {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-past {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-full {
            background-color: #fff3cd;
            color: #856404;
        }

        .no-events {
            text-align: center;
            padding: 40px;
            color: #7a7c90;
            font-style: italic;
        }

        .no-events i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: #37477b;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2c3050;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .btn-view-ticket {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            background: #1976d2;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-view-ticket:hover {
            background: #1565c0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(25, 118, 210, 0.3);
        }

        .btn-view-ticket i {
            margin-right: 4px;
        }

        .event-status {
            text-align: center;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .events-container {
                padding: 15px;
                padding-top: 90px;
            }

            .events-header h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 1.2rem;
                padding: 12px 20px;
            }

            .events-table-container {
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .events-container {
                padding: 10px;
                padding-top: 70px;
            }
            
            .events-header h1 {
                font-size: 1.8rem;
            }

            .events-header p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.1rem;
                padding: 10px 18px;
                border-radius: 12px 12px 0 0;
            }

            .events-table-container {
                padding: 15px;
                border-radius: 0 0 12px 12px;
            }

            /* Make table responsive with cards on mobile */
            .events-table {
                display: block;
                width: 100%;
            }

            .events-table thead {
                display: none;
            }

            .events-table tbody,
            .events-table tr,
            .events-table td {
                display: block;
                width: 100%;
            }

            .events-table tr {
                margin-bottom: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .events-table td {
                padding: 12px 15px;
                border-bottom: 1px solid #f0f0f0;
                text-align: left !important;
            }

            .events-table td:last-child {
                border-bottom: none;
            }

            /* Add labels for mobile view */
            .events-table td:before {
                content: attr(data-label);
                font-weight: 700;
                color: #37477b;
                display: block;
                margin-bottom: 5px;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .events-table td:nth-child(1):before {
                content: "Event Details";
            }

            .events-table td:nth-child(2):before {
                content: "Date & Time";
            }

            .events-table td:nth-child(3):before {
                content: "Location";
            }

            .events-table td:nth-child(4):before {
                content: "Capacity";
            }

            .events-table td:nth-child(5):before {
                content: "Status";
            }

            .events-table td:nth-child(6):before {
                content: "Actions";
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .no-events {
                padding: 30px 15px;
            }
        }

        @media (max-width: 480px) {
            .events-container {
                padding: 8px;
                padding-top: 60px;
            }

            .events-header {
                margin-bottom: 25px;
            }

            .events-header h1 {
                font-size: 1.5rem;
            }

            .events-header p {
                font-size: 0.9rem;
            }

            .section-title {
                font-size: 1rem;
                padding: 10px 15px;
                gap: 8px;
            }

            .events-table-container {
                padding: 12px;
            }

            .events-table tr {
                margin-bottom: 12px;
                border-radius: 8px;
            }

            .events-table td {
                padding: 10px 12px;
            }

            .event-title {
                font-size: 0.95rem;
            }

            .event-description {
                font-size: 0.8rem;
            }

            .status-badge {
                padding: 5px 10px;
                font-size: 0.75rem;
            }

            .btn {
                padding: 7px 14px;
                font-size: 0.75rem;
            }

            .btn-view-ticket {
                padding: 5px 10px;
                font-size: 11px;
            }

            .no-events {
                padding: 25px 10px;
            }

            .no-events i {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 360px) {
            .events-header h1 {
                font-size: 1.3rem;
            }

            .section-title {
                font-size: 0.9rem;
                padding: 8px 12px;
            }

            .events-table td {
                padding: 8px 10px;
            }

            .event-title {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="../../assets/images/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" alt="National University Logo" class="logo-img">
                </div>
                <span class="logo-text">National University</span>
            </div>
            <nav class="nav-links">
                <a href="<?= $user_role === 'admin' ? '../admin/dashboard.php' : '../student/dashboard.php' ?>" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="<?= $user_role === 'admin' ? '../admin/calendar.php' : '../student/calendar.php' ?>" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="view_events.php" class="nav-link active">VIEW EVENTS</a>
                <?php if ($user_role === 'admin'): ?>
                <span class="nav-divider">|</span>
                <a href="../admin/attendance.php" class="nav-link">ATTENDANCE</a>
                <?php endif; ?>
            </nav>
            <div class="header-icons">
                <button onclick="showProfileMenu()" class="icon-btn user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <button class="icon-btn hamburger-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="events-container">
        <div class="events-header">
            <h1><i class="fas fa-calendar-alt"></i> Event Schedule</h1>
            <p>View all upcoming and previous events</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="message success" style="max-width: 1200px; margin: 0 auto 30px auto; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 8px; text-align: center; font-weight: 600;">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <div class="events-section">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Upcoming Events (<?= count($upcoming_events) ?>)
            </h2>
            <div class="events-table-container">
                <?php if (empty($upcoming_events)): ?>
                    <div class="no-events">
                        <i class="fas fa-calendar-times"></i>
                        <p>No upcoming events scheduled</p>
                    </div>
                <?php else: ?>
                    <table class="events-table">
                        <thead>
                            <tr>
                                <th>Event Details</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <?php if ($user_role === 'admin'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcoming_events as $event): ?>
                                <?php
                                $is_registered = in_array($event['id'], $registered_events);
                                $current_registrations = 0;
                                
                                $count_query = "SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ?";
                                $count_stmt = $conn->prepare($count_query);
                                $count_stmt->bind_param("i", $event['id']);
                                $count_stmt->execute();
                                $count_result = $count_stmt->get_result();
                                $count_row = $count_result->fetch_assoc();
                                $current_registrations = $count_row['count'];
                                $count_stmt->close();
                                
                                $is_full = $current_registrations >= $event['capacity'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="event-title"><?= htmlspecialchars($event['title']) ?></div>
                                        <div class="event-description"><?= htmlspecialchars($event['description']) ?></div>
                                    </td>
                                    <td>
                                        <div class="event-date"><?= date('M j, Y', strtotime($event['start_date'])) ?></div>
                                        <div class="event-time"><?= date('g:i A', strtotime($event['start_date'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($event['location']) ?>
                                        </div>
                                    </td>
                                    <td class="event-capacity">
                                        <?= $current_registrations ?> / <?= $event['capacity'] ?>
                                    </td>
                                    <td class="event-status">
                                        <?php if ($user_role === 'student'): ?>
                                            <div>
                                                <?php if ($is_registered): ?>
                                                    <span class="status-badge status-registered">Registered</span>
                                                    <?php 
                                                    $ticket = $registered_tickets[$event['id']] ?? null;
                                                    if ($ticket): 
                                                    ?>
                                                        <br>
                                                        <a href="../student/view_ticket.php?ticket=<?= htmlspecialchars($ticket) ?>" 
                                                           class="btn-view-ticket">
                                                            <i class="fas fa-ticket-alt"></i> View Ticket
                                                        </a>
                                                    <?php endif; ?>
                                                <?php elseif ($is_full): ?>
                                                    <span class="status-badge status-full">Full</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-upcoming">Available</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="status-badge status-upcoming">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($user_role === 'admin'): ?>
                                        <td class="action-buttons">
                                            <a href="../admin/edit_event_page.php?id=<?= $event['id'] ?>" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="../admin/delete_event_page.php?id=<?= $event['id'] ?>" class="btn btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this event?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="events-section">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Previous Events (<?= count($previous_events) ?>)
            </h2>
            <div class="events-table-container">
                <?php if (empty($previous_events)): ?>
                    <div class="no-events">
                        <i class="fas fa-calendar-check"></i>
                        <p>No previous events found</p>
                    </div>
                <?php else: ?>
                    <table class="events-table">
                        <thead>
                            <tr>
                                <th>Event Details</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Attendance</th>
                                <th>Status</th>
                                <?php if ($user_role === 'admin'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($previous_events as $event): ?>
                                <?php
                                $is_registered = in_array($event['id'], $registered_events);
                                $current_registrations = 0;
                                
                                $count_query = "SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ?";
                                $count_stmt = $conn->prepare($count_query);
                                $count_stmt->bind_param("i", $event['id']);
                                $count_stmt->execute();
                                $count_result = $count_stmt->get_result();
                                $count_row = $count_result->fetch_assoc();
                                $current_registrations = $count_row['count'];
                                $count_stmt->close();
                                ?>
                                <tr>
                                    <td>
                                        <div class="event-title"><?= htmlspecialchars($event['title']) ?></div>
                                        <div class="event-description"><?= htmlspecialchars($event['description']) ?></div>
                                    </td>
                                    <td>
                                        <div class="event-date"><?= date('M j, Y', strtotime($event['start_date'])) ?></div>
                                        <div class="event-time"><?= date('g:i A', strtotime($event['start_date'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($event['location']) ?>
                                        </div>
                                    </td>
                                    <td class="event-capacity">
                                        <?= $current_registrations ?> / <?= $event['capacity'] ?>
                                    </td>
                                    <td class="event-status">
                                        <?php if ($user_role === 'student'): ?>
                                            <div>
                                                <?php if ($is_registered): ?>
                                                    <span class="status-badge status-registered">Attended</span>
                                                    <?php 
                                                    $ticket = $registered_tickets[$event['id']] ?? null;
                                                    if ($ticket): 
                                                    ?>
                                                        <br>
                                                        <a href="../student/view_ticket.php?ticket=<?= htmlspecialchars($ticket) ?>" 
                                                           class="btn-view-ticket">
                                                            <i class="fas fa-ticket-alt"></i> View Ticket
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="status-badge status-past">Missed</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="status-badge status-past">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($user_role === 'admin'): ?>
                                        <td class="action-buttons">
                                            <a href="../admin/edit_event_page.php?id=<?= $event['id'] ?>" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="../admin/delete_event_page.php?id=<?= $event['id'] ?>" class="btn btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this event?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../../includes/profile_menu_popup.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.events-table tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
            const successMessage = document.querySelector('.message.success');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>
