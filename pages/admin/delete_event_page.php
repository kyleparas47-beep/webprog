<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../includes/config.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$message_type = '';

// Fetch event data
$event = null;
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
    
    if (!$event) {
        header("Location: ../shared/view_events.php");
        exit();
    }
} else {
    header("Location: ../shared/view_events.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    // First, delete all registrations for this event
    $delete_registrations = $conn->prepare("DELETE FROM event_registrations WHERE event_id = ?");
    $delete_registrations->bind_param("i", $event_id);
    $delete_registrations->execute();
    $delete_registrations->close();
    
    // Then delete the event
    $delete_event = $conn->prepare("DELETE FROM events WHERE id = ?");
    $delete_event->bind_param("i", $event_id);
    
    if ($delete_event->execute()) {
        // Redirect to view events with success message
        header("Location: ../shared/view_events.php?deleted=1");
        exit();
    } else {
        $message = 'Failed to delete event. Please try again.';
        $message_type = 'error';
    }
    $delete_event->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event - National University</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap');
        
        *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #4a4e9e;
            color: #31344d;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .delete-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px;
        }

        .delete-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .delete-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .delete-form-container {
            background: linear-gradient(135deg, #dde0f0 0%, #e8eaf6 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: scale(0.8);
            animation: deleteSlideIn 0.2s ease-out forwards;
        }

        @keyframes deleteSlideIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }

        .warning-box i {
            font-size: 2.5rem;
            color: #856404;
            margin-bottom: 10px;
        }

        .warning-box h3 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 1.2rem;
        }

        .warning-box p {
            color: #856404;
            margin: 0;
            font-size: 0.95rem;
        }

        .event-details {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .event-details h4 {
            color: #37477b;
            margin: 0 0 15px 0;
            font-size: 1.1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #7a7c90;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #37477b;
            font-weight: 500;
            text-align: right;
            max-width: 60%;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .delete-container {
                padding: 10px;
                padding-top: 80px;
            }
            
            .delete-header h1 {
                font-size: 1.8rem;
            }
            
            .form-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            
            .detail-value {
                text-align: left;
                max-width: 100%;
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
                <a href="dashboard.php" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="calendar.php" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="../shared/view_events.php" class="nav-link active">VIEW EVENTS</a>
                <span class="nav-divider">|</span>
                <a href="attendance.php" class="nav-link">ATTENDANCE</a>
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

    <div class="delete-container">
        <div class="delete-header">
            <h1><i class="fas fa-trash-alt"></i> Delete Event</h1>
            <p>Confirm deletion of this event</p>
        </div>

        <div class="delete-form-container">
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Warning: This action cannot be undone!</h3>
                <p>Deleting this event will also remove all student registrations for this event.</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="message <?= $message_type ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="event-details">
                <h4><i class="fas fa-info-circle"></i> Event Details</h4>
                <div class="detail-row">
                    <span class="detail-label">Title:</span>
                    <span class="detail-value"><?= htmlspecialchars($event['title']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value"><?= htmlspecialchars($event['event_type']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Start Date:</span>
                    <span class="detail-value"><?= date('M j, Y g:i A', strtotime($event['start_date'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Date:</span>
                    <span class="detail-value"><?= date('M j, Y g:i A', strtotime($event['end_date'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value"><?= htmlspecialchars($event['location'] ?: 'Not specified') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Capacity:</span>
                    <span class="detail-value"><?= $event['capacity'] ?> people</span>
                </div>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="delete_event" value="1">
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you absolutely sure you want to delete this event? This action cannot be undone and will remove all student registrations.')">
                        <i class="fas fa-trash"></i> Yes, Delete Event
                    </button>
                    <a href="../shared/view_events.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php include '../../includes/profile_menu_popup.php'; ?>
</body>
</html>
