<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Calendar - National University</title>
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="calendar_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .calendar-action-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            gap: 12px;
            z-index: 1000;
        }
        
        .btn-save-updates, .btn-update-calendar {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-save-updates {
            background: #1976d2;
            color: white;
        }
        
        .btn-save-updates:hover {
            background: #1565c0;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }
        
        .btn-update-calendar {
            background: #4caf50;
            color: white;
        }
        
        .btn-update-calendar:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
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
                <a href="admin_calendar.php" class="nav-link active">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="#" class="nav-link">VIEW EVENTS</a>
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

    <div class="calendar-wrapper">
        <aside class="calendar-sidebar-left">
            <button class="create-btn" onclick="openEventModal()">
                <i class="fas fa-plus"></i> CREATE
            </button>

            <div class="mini-calendar">
                <div class="mini-calendar-header">
                    <button class="mini-nav-btn" onclick="prevMonth()"><i class="fas fa-chevron-left"></i></button>
                    <span class="mini-month-year">January 2025</span>
                    <button class="mini-nav-btn" onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="mini-calendar-grid"></div>
            </div>

            <div class="upcoming-events">
                <h3>Upcoming Events</h3>
                <p class="loading">Loading events...</p>
            </div>
        </aside>

        <main class="calendar-main">
            <div class="calendar-header">
                <div class="calendar-title-section">
                    <button class="nav-btn" onclick="navigateCalendar(-1)"><i class="fas fa-chevron-left"></i></button>
                    <button class="nav-btn" onclick="navigateCalendar(1)"><i class="fas fa-chevron-right"></i></button>
                    <button class="today-btn" onclick="goToToday()">Today</button>
                    <h2>Loading...</h2>
                </div>
                <div class="calendar-controls">
                    <div class="search-calendar-box">
                        <input type="text" id="searchEventsInput" placeholder="Search events..." onkeyup="searchEvents()">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="view-switcher">
                <button class="view-switch-btn" onclick="changeView('day', this)">Day</button>
                <button class="view-switch-btn active-view" onclick="changeView('week', this)">Week</button>
                <button class="view-switch-btn" onclick="changeView('month', this)">Month</button>
            </div>

            <div class="event-filters">
                <button class="filter-btn active" data-filter="all">All Events</button>
                <button class="filter-btn" data-filter="webinar">Webinar</button>
                <button class="filter-btn" data-filter="seminars">Seminars</button>
                <button class="filter-btn" data-filter="workshop">Workshop</button>
            </div>

            <div class="calendar-content">
                <!-- Calendar will be rendered here by JavaScript -->
            </div>

            <div class="calendar-action-buttons">
                <button class="btn-save-updates" onclick="saveUpdates()">
                    <i class="fas fa-save"></i> SAVE UPDATES
                </button>
                <button class="btn-update-calendar" onclick="updateUniversityCalendar()">
                    <i class="fas fa-sync-alt"></i> UPDATE UNIVERSITY CALENDAR
                </button>
            </div>
        </main>
    </div>

    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEventModal()">&times;</span>
            <h2 id="modalTitle">Create Event</h2>
            <form id="eventForm" onsubmit="saveEvent(event)">
                <input type="hidden" id="eventId" name="event_id">
                
                <label for="eventTitle">Event Title:</label>
                <input type="text" id="eventTitle" name="title" required>
                
                <label for="eventDescription">Description:</label>
                <textarea id="eventDescription" name="description" rows="3"></textarea>
                
                <label for="eventType">Event Type:</label>
                <select id="eventType" name="event_type" required>
                    <option value="Events">Events</option>
                    <option value="Webinar">Webinar</option>
                    <option value="Seminars">Seminars</option>
                    <option value="Workshop">Workshop</option>
                </select>
                
                <label for="startDate">Start Date & Time:</label>
                <input type="datetime-local" id="startDate" name="start_date" required>
                
                <label for="endDate">End Date & Time:</label>
                <input type="datetime-local" id="endDate" name="end_date" required>
                
                <label for="location">Location:</label>
                <input type="text" id="location" name="location">
                
                <div class="modal-buttons">
                    <button type="submit" class="btn-save">Save Event</button>
                    <button type="button" class="btn-delete" id="deleteBtn" onclick="deleteEvent()" style="display:none;">Delete Event</button>
                    <button type="button" class="btn-cancel" onclick="closeEventModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'profile_menu_popup.php'; ?>
    <script src="calendar_admin.js"></script>
</body>
</html>
