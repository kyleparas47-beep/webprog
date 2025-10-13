<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/config.php';

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
        header("Location: view_events.php");
        exit();
    }
} else {
    header("Location: view_events.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_type = trim($_POST['event_type'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = isset($_POST['capacity']) ? (int)$_POST['capacity'] : 50;
    
    // Validation
    if (empty($title) || empty($event_type) || empty($start_date) || empty($end_date)) {
        $message = 'Required fields are missing.';
        $message_type = 'error';
    } else {
        // Normalize datetime inputs
        $normalize_datetime = function ($value) {
            $value = trim((string)$value);
            if ($value === '') return '';
            $value = str_replace('T', ' ', $value);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $value)) {
                $value .= ':00';
            }
            return $value;
        };

        $start_date = $normalize_datetime($start_date);
        $end_date = $normalize_datetime($end_date);

        // Validate dates
        try {
            $start_dt = new DateTime($start_date);
            $end_dt = new DateTime($end_date);
            if ($end_dt <= $start_dt) {
                $message = 'End date/time must be after start date/time.';
                $message_type = 'error';
            } else {
                // Ensure event_type is within allowed values
                $allowed_types = ['Events', 'Webinar', 'Seminars', 'Workshop'];
                if (!in_array($event_type, $allowed_types, true)) {
                    $event_type = 'Events';
                }

                // Update event
                $update_stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_type = ?, start_date = ?, end_date = ?, location = ?, capacity = ? WHERE id = ?");
                $update_stmt->bind_param("ssssssii", $title, $description, $event_type, $start_date, $end_date, $location, $capacity, $event_id);
                
                if ($update_stmt->execute()) {
                    $message = 'Event updated successfully!';
                    $message_type = 'success';
                    
                    // Update local event data for display
                    $event['title'] = $title;
                    $event['description'] = $description;
                    $event['event_type'] = $event_type;
                    $event['start_date'] = $start_date;
                    $event['end_date'] = $end_date;
                    $event['location'] = $location;
                    $event['capacity'] = $capacity;
                } else {
                    $message = 'Failed to update event. Please try again.';
                    $message_type = 'error';
                }
                $update_stmt->close();
            }
        } catch (Exception $e) {
            $message = 'Invalid date/time format.';
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - National University</title>
    <link rel="stylesheet" href="student.css">
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

        .edit-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px;
        }

        .edit-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .edit-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .edit-form-container {
            background: linear-gradient(135deg, #dde0f0 0%, #e8eaf6 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: scale(0.8);
            animation: editSlideIn 0.2s ease-out forwards;
        }

        @keyframes editSlideIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #37477b;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e9fb;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #37477b;
            font-weight: 500;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }

        /* Specific styling for datetime inputs to ensure full text visibility */
        input[type="datetime-local"] {
            padding: 12px 18px; /* Slightly more padding for datetime inputs */
            font-size: 0.875rem;
            letter-spacing: 0.5px; /* Better spacing for readability */
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #37477b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1), 0 0 0 3px rgba(55, 71, 123, 0.1);
        }

        /* Specific styling for datetime-local inputs */
        input[type="datetime-local"] {
            width: 100%;
            box-sizing: border-box;
            max-width: 100%;
            overflow: hidden;
        }

        /* Ensure datetime inputs don't overflow their containers */
        .form-group input[type="datetime-local"] {
            width: 100%;
            min-width: 200px; /* Ensure enough space for full datetime display */
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px; /* Increased gap for better spacing */
        }

        .form-row .form-group {
            min-width: 0; /* Prevents grid items from overflowing */
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            margin-top: 30px;
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

        .btn-primary {
            background-color: #37477b;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2c3050;
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

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .edit-container {
                padding: 10px;
                padding-top: 80px;
            }
            
            .edit-header h1 {
                font-size: 1.8rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .form-buttons {
                flex-direction: column;
                gap: 10px;
            }

            /* Ensure datetime inputs fit properly on mobile */
            .form-group input[type="datetime-local"] {
                width: 100%;
                font-size: 14px; /* Smaller font size, prevents zoom on iOS */
                min-width: 180px; /* Minimum width for mobile */
                padding: 10px 15px;
            }
        }

        /* Additional fix for datetime-local inputs */
        @media (max-width: 480px) {
            .edit-form-container {
                padding: 20px;
            }
            
            .form-group input[type="datetime-local"] {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="assets/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" alt="National University Logo" class="logo-img">
                </div>
                <span class="logo-text">National University</span>
            </div>
            <nav class="nav-links">
                <a href="admin_page.php" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="admin_calendar.php" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="view_events.php" class="nav-link active">VIEW EVENTS</a>
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

    <div class="edit-container">
        <div class="edit-header">
            <h1><i class="fas fa-edit"></i> Edit Event</h1>
            <p>Update event details and settings</p>
        </div>

        <div class="edit-form-container">
            <?php if (!empty($message)): ?>
                <div class="message <?= $message_type ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="update_event" value="1">
                
                <div class="form-group">
                    <label class="form-label" for="title">Event Title:</label>
                    <input type="text" id="title" name="title" class="form-input" 
                           value="<?= htmlspecialchars($event['title']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Description:</label>
                    <textarea id="description" name="description" class="form-textarea" 
                              rows="3"><?= htmlspecialchars($event['description']) ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="event_type">Event Type:</label>
                        <select id="event_type" name="event_type" class="form-select" required>
                            <option value="Events" <?= $event['event_type'] === 'Events' ? 'selected' : '' ?>>Events</option>
                            <option value="Webinar" <?= $event['event_type'] === 'Webinar' ? 'selected' : '' ?>>Webinar</option>
                            <option value="Seminars" <?= $event['event_type'] === 'Seminars' ? 'selected' : '' ?>>Seminars</option>
                            <option value="Workshop" <?= $event['event_type'] === 'Workshop' ? 'selected' : '' ?>>Workshop</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="capacity">Capacity:</label>
                        <input type="number" id="capacity" name="capacity" class="form-input" 
                               min="1" max="1000" value="<?= $event['capacity'] ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="start_date">Start Date & Time:</label>
                        <input type="datetime-local" id="start_date" name="start_date" class="form-input" 
                               value="<?= date('Y-m-d\TH:i', strtotime($event['start_date'])) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="end_date">End Date & Time:</label>
                        <input type="datetime-local" id="end_date" name="end_date" class="form-input" 
                               value="<?= date('Y-m-d\TH:i', strtotime($event['end_date'])) ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="location">Location:</label>
                    <input type="text" id="location" name="location" class="form-input" 
                           value="<?= htmlspecialchars($event['location']) ?>">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                    <a href="view_events.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Events
                    </a>
                    <a href="delete_event_page.php?id=<?= $event['id'] ?>" class="btn btn-danger" 
                       onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Event
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'profile_menu_popup.php'; ?>

    <script>
        // Auto-hide success messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
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

        // Form validation
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(this.value);
            
            if (endDate <= startDate) {
                alert('End date/time must be after start date/time.');
                this.value = '';
            }
        });
    </script>
</body>
</html>
