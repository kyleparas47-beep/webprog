<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Calendar - National University</title>
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="calendar_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .registered-events-panel {
            position: fixed;
            right: 0;
            top: 70px;
            width: 320px;
            height: calc(100vh - 70px);
            background: white;
            border-left: 1px solid #e0e0e0;
            padding: 20px;
            overflow-y: auto;
            z-index: 100;
        }
        
        .registered-events-panel h3 {
            color: #1a237e;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .registered-event-item {
            background: #f5f5f5;
            border-left: 4px solid #1976d2;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        
        .registered-event-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .registered-event-date {
            font-size: 12px;
            color: #666;
        }
        
        .calendar-wrapper {
            margin-right: 340px;
        }
        
        .success-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 10000;
            text-align: center;
        }
        
        .success-popup.show {
            display: block;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
        
        .success-popup .icon {
            font-size: 48px;
            color: #4caf50;
            margin-bottom: 15px;
        }
        
        .success-popup h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .success-popup p {
            color: #666;
        }
        
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
        }
        
        .popup-overlay.show {
            display: block;
        }
        
        .event-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
        
        .btn-register {
            background: #1976d2;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        
        .btn-register:hover {
            background: #1565c0;
        }
        
        .btn-registered {
            background: #4caf50;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            cursor: default;
            font-size: 13px;
        }
        
        /* Ticket Popup Styles */
        .ticket-card-popup {
            background: linear-gradient(135deg, #1e5ba8 0%, #2d6bb3 100%);
            border-radius: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-header-popup {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-bottom: 2px dashed rgba(255, 255, 255, 0.3);
            position: relative;
        }
        
        .ticket-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
        }
        
        .ticket-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .university-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
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
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }
        
        .status-badge-popup {
            position: absolute;
            top: 20px;
            right: 60px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .event-title-popup {
            font-size: 20px;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }
        
        .event-type-popup {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
            margin: 0;
        }
        
        .ticket-body-popup {
            padding: 25px;
        }
        
        .ticket-section {
            margin-bottom: 15px;
        }
        
        .section-label {
            font-size: 11px;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .section-value {
            font-size: 15px;
            font-weight: 600;
        }
        
        .ticket-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .qr-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        
        .ticket-number-display {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
        }
        
        .ticket-number-label {
            font-size: 11px;
            opacity: 0.7;
            margin-bottom: 5px;
        }
        
        .ticket-number-value {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
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
            background: rgba(255, 152, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        
        /* Registration Form Styles */
        .form-row {
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group label i {
            color: #4a5bb8;
            margin-right: 5px;
            width: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4a5bb8;
            box-shadow: 0 0 0 3px rgba(74, 91, 184, 0.1);
        }
        
        .form-group input::placeholder {
            color: #999;
        }
        
        #registrationModal .modal-content {
            padding: 35px;
        }
        
        #registrationModal h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        
        #registrationModal h2 i {
            color: #4a5bb8;
        }
        
        /* Ongoing Events Section */
        .ongoing-events {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .ongoing-events h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .ongoing-event-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            background: linear-gradient(135deg, #36408b 0%, #2d3470 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(54, 64, 139, 0.3);
        }
        
        .ongoing-event-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(54, 64, 139, 0.4);
        }
        
        .ongoing-color-bar {
            width: 4px;
            border-radius: 2px;
            background: white;
        }
        
        .ongoing-event-details {
            flex: 1;
        }
        
        .ongoing-event-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .ongoing-event-time {
            font-size: 11px;
            opacity: 0.9;
        }
        
        .no-ongoing-events {
            text-align: center;
            padding: 15px;
            color: #999;
            font-size: 13px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
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
                <a href="student_page.php" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="student_calendar.php" class="nav-link active">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="view_events.php" class="nav-link">VIEW EVENTS</a>
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
            <div class="mini-calendar">
                <div class="mini-calendar-header">
                    <button class="mini-nav-btn" onclick="prevMonth()"><i class="fas fa-chevron-left"></i></button>
                    <span class="mini-month-year">January 2025</span>
                    <button class="mini-nav-btn" onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="mini-calendar-grid"></div>
            </div>

            <div class="ongoing-events">
                <h3><i class="fas fa-circle" style="color: #4caf50; font-size: 10px; animation: pulse 2s infinite;"></i> Ongoing Events</h3>
                <p class="loading">Loading events...</p>
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

            <div class="calendar-content"></div>
        </main>
    </div>

    <div class="registered-events-panel">
        <h3><i class="fas fa-check-circle"></i> My Registered Events</h3>
        <div id="registeredEventsList">
            <p style="color: #999; text-align: center;">No registered events yet</p>
        </div>
    </div>

    <div class="popup-overlay" id="popupOverlay" onclick="closeSuccessPopup()"></div>
    <div class="success-popup" id="successPopup">
        <div class="icon">
            <i class="fas fa-check-circle" style="font-size: 48px; color: #4caf50;"></i>
        </div>
        <h2>Success!</h2>
        <p>You have successfully pre-registered for this event.</p>
    </div>

    <div id="eventViewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEventViewModal()">&times;</span>
            <h2 id="viewEventTitle"></h2>
            <div id="viewEventDetails"></div>
            <div id="viewEventActions"></div>
        </div>
    </div>

    <div id="registrationModal" class="modal">
        <div class="modal-content" style="max-width: 550px;">
            <span class="close" onclick="closeRegistrationModal()">&times;</span>
            <h2 style="color: #333; margin-bottom: 25px; font-size: 24px;">
                <i class="fas fa-user-plus"></i> Pre-Register for Event
            </h2>
            <form id="registrationForm" onsubmit="submitRegistration(event)">
                <input type="hidden" id="regEventId" name="event_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentName">
                            <i class="fas fa-user"></i> Full Name:
                        </label>
                        <input type="text" id="studentName" name="student_name" required placeholder="Enter your full name">
                    </div>
                </div>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="studentSection">
                            <i class="fas fa-users"></i> Section:
                        </label>
                        <input type="text" id="studentSection" name="section" required placeholder="e.g., BSIT-3A">
                    </div>
                    
                    <div class="form-group">
                        <label for="studentCourse">
                            <i class="fas fa-graduation-cap"></i> Course:
                        </label>
                        <input type="text" id="studentCourse" name="course" required placeholder="e.g., Bachelor of Science in IT">
                    </div>
                </div>
                
                <div class="modal-buttons" style="margin-top: 30px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-save" style="flex: 2;">
                        <i class="fas fa-check-circle"></i> Submit Registration
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeRegistrationModal()" style="flex: 1;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="ticketModal" class="modal">
        <div class="modal-content" style="max-width: 500px; padding: 0; overflow: hidden;">
            <div class="ticket-card-popup">
                <div class="ticket-header-popup">
                    <div class="ticket-close-btn" onclick="closeTicketModal()">&times;</div>
                    <div class="university-logo">
                        <div class="logo-circle">
                            <img src="assets/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" 
                                 alt="NU Logo" 
                                 style="width: 32px; height: 32px; object-fit: contain;">
                        </div>
                        <h1 class="university-name">NationalU</h1>
                    </div>
                    <span class="status-badge-popup">PRE-REGISTERED</span>
                    <h2 class="event-title-popup" id="ticketEventTitle"></h2>
                    <p class="event-type-popup" id="ticketEventType"></p>
                </div>
                
                <div class="ticket-body-popup">
                    <div class="ticket-section">
                        <div class="section-label">ADDRESS</div>
                        <div class="section-value" id="ticketLocation"></div>
                    </div>
                    
                    <div class="ticket-grid">
                        <div class="ticket-section">
                            <div class="section-label">STUDENT</div>
                            <div class="section-value" id="ticketStudentName"></div>
                        </div>
                        <div class="ticket-section">
                            <div class="section-label">SECTION</div>
                            <div class="section-value" id="ticketSection"></div>
                        </div>
                    </div>
                    
                    <div class="ticket-section">
                        <div class="section-label">COURSE</div>
                        <div class="section-value" id="ticketCourse"></div>
                    </div>
                    
                    <div class="ticket-section">
                        <div class="section-label">EVENT DATE & TIME</div>
                        <div class="section-value" id="ticketDateTime"></div>
                    </div>
                    
                    <div class="qr-section">
                        <div class="qr-code" id="ticketQRCode"></div>
                    </div>
                    
                    <div class="ticket-number-display">
                        <div class="ticket-number-label">Ticket No.</div>
                        <div class="ticket-number-value" id="ticketNumber"></div>
                    </div>
                    
                    <div class="attendance-status not-attended">
                        <i class="fas fa-clock"></i> Present this ticket at the event venue
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="printTicket()">
                            <i class="fas fa-print"></i> Print Ticket
                        </button>
                        <button class="btn btn-secondary" onclick="closeTicketModal()">
                            <i class="fas fa-check"></i> Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'profile_menu_popup.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="calendar_functions.js"></script>
    <script>
        const studentId = <?php echo $student_id; ?>;
        let currentDate = new Date();
        let currentView = 'week';
        let allEvents = [];
        let registeredEvents = [];
        let filteredEventType = 'all';

        const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const DAYS_SHORT = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        document.addEventListener('DOMContentLoaded', function() {
            loadEvents();
            loadRegisteredEvents();
            renderCalendar();
            updateMiniCalendar();
            setupEventListeners();
            displayOngoingEvents();
        });

        function setupEventListeners() {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filteredEventType = this.dataset.filter;
                    renderCalendar();
                });
            });
        }

        function loadEvents() {
            fetch('get_events.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allEvents = data.events.map(event => ({
                            ...event,
                            start: parseApiDate(event.start_date),
                            end: parseApiDate(event.end_date)
                        }));
                        renderCalendar();
                        displayOngoingEvents();
                        displayUpcomingEvents();
                    }
                })
                .catch(error => console.error('Error loading events:', error));
        }

        function parseApiDate(value) {
            if (!value) return null;
            const str = typeof value === 'string' ? value.replace(' ', 'T') : value;
            return new Date(str);
        }
        
        function displayOngoingEvents() {
            const container = document.querySelector('.ongoing-events');
            if (!container) return;
            
            const today = new Date();
            const todayStart = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0);
            const todayEnd = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59);
            
            let html = '<h3><i class="fas fa-circle" style="color: #36408b; font-size: 10px; animation: pulse 2s infinite;"></i> Ongoing Events</h3>';
            
            const ongoing = allEvents.filter(e => {
                const eventStart = new Date(e.start);
                const eventEnd = new Date(e.end);
                // Event is ongoing if it started today or is currently in progress
                return (eventStart <= todayEnd && eventEnd >= todayStart);
            }).sort((a, b) => new Date(a.start) - new Date(b.start));
            
            if (ongoing.length === 0) {
                html += '<div class="no-ongoing-events"><i class="fas fa-calendar-check"></i> No events happening today</div>';
            } else {
                ongoing.forEach(event => {
                    const eventStart = new Date(event.start);
                    const isHappeningNow = eventStart <= today && new Date(event.end) >= today;
                    const status = isHappeningNow ? '🔴 LIVE NOW' : 'Today';
                    
                    html += `<div class="ongoing-event-item" onclick="viewEventDetails(${event.id})">
                                <div class="ongoing-color-bar"></div>
                                <div class="ongoing-event-details">
                                    <p class="ongoing-event-title">${event.title}</p>
                                    <p class="ongoing-event-time">${status} | ${formatTime(event.start)}</p>
                                </div>
                             </div>`;
                });
            }
            
            container.innerHTML = html;
        }

        function loadRegisteredEvents() {
            return fetch('get_registered_events.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Registered events response:', data); // Debug log
                    if (data.success) {
                        registeredEvents = data.events;
                        console.log('Loaded registered events:', registeredEvents); // Debug log
                        displayRegisteredEvents();
                        return data.events;
                    } else {
                        console.error('Failed to load registered events:', data.message);
                        if (data.error_details) {
                            console.error('Error details:', data.error_details);
                        }
                        return [];
                    }
                })
                .catch(error => {
                    console.error('Error loading registered events:', error);
                    return [];
                });
        }

        function displayRegisteredEvents() {
            const container = document.getElementById('registeredEventsList');
            if (registeredEvents.length === 0) {
                container.innerHTML = '<p style="color: #999; text-align: center;">No registered events yet</p>';
                return;
            }

            let html = '';
            registeredEvents.forEach(event => {
                const attendedBadge = event.attended 
                    ? '<span style="color: #4caf50; font-size: 12px;"><i class="fas fa-check-circle"></i> Attended</span>'
                    : '<span style="color: #ff9800; font-size: 12px;"><i class="fas fa-clock"></i> Pending</span>';
                
                // Show View Ticket button only if ticket number exists
                const ticketButton = event.ticket_number 
                    ? `<button onclick="window.location.href='view_ticket.php?ticket=${event.ticket_number}'" 
                              style="margin-top: 8px; width: 100%; padding: 8px; background: #1976d2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                          <i class="fas fa-ticket-alt"></i> View Ticket
                       </button>`
                    : `<div style="margin-top: 8px; padding: 8px; background: #f5f5f5; border-radius: 4px; text-align: center; font-size: 11px; color: #999;">
                          <i class="fas fa-info-circle"></i> Old registration (no ticket)
                       </div>`;
                
                html += `<div class="registered-event-item">
                            <div class="registered-event-title">${event.title}</div>
                            <div class="registered-event-date">
                                <i class="fas fa-calendar"></i> ${formatEventDate(new Date(event.start_date))}
                            </div>
                            <div class="registered-event-date">
                                <i class="fas fa-clock"></i> ${formatTime(new Date(event.start_date))}
                            </div>
                            <div style="margin-top: 8px;">
                                ${attendedBadge}
                            </div>
                            ${ticketButton}
                        </div>`;
            });
            container.innerHTML = html;
        }
        
        function showExistingTicket(eventId, ticketNumber) {
            // Find the event
            const event = allEvents.find(e => e.id == eventId);
            if (!event) {
                window.location.href = 'view_ticket.php?ticket=' + ticketNumber;
                return;
            }
            
            // Find the registration from our loaded data
            const registration = registeredEvents.find(r => r.id == eventId);
            if (!registration) {
                window.location.href = 'view_ticket.php?ticket=' + ticketNumber;
                return;
            }
            
            // Show the modal with the data we have
            showTicketModalFromRegistration(registration, event);
        }
        
        function showTicketModalFromRegistration(registration, event) {
            // Populate ticket modal with existing registration
            document.getElementById('ticketEventTitle').textContent = event.title;
            document.getElementById('ticketEventType').textContent = event.event_type;
            document.getElementById('ticketLocation').textContent = event.location || 'To be announced';
            
            // Get student info from the registration data in get_registered_events
            // Since we don't store it separately, we'll need to fetch it
            fetch('get_registration_info.php?ticket=' + registration.ticket_number)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('ticketStudentName').textContent = data.student_name;
                        document.getElementById('ticketSection').textContent = data.section;
                        document.getElementById('ticketCourse').textContent = data.course;
                        
                        // Format date and time
                        const startDate = new Date(event.start_date);
                        const dateStr = startDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        const timeStr = startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                        document.getElementById('ticketDateTime').textContent = `${dateStr} at ${timeStr}`;
                        
                        document.getElementById('ticketNumber').textContent = registration.ticket_number;
                        
                        // Generate QR code
                        const qrContainer = document.getElementById('ticketQRCode');
                        qrContainer.innerHTML = ''; // Clear previous QR code
                        new QRCode(qrContainer, {
                            text: registration.ticket_number,
                            width: 200,
                            height: 200,
                            colorDark: "#1e5ba8",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                        
                        // Show modal
                        document.getElementById('ticketModal').style.display = 'block';
                    } else {
                        // Fallback to view_ticket.php
                        window.location.href = 'view_ticket.php?ticket=' + registration.ticket_number;
                    }
                })
                .catch(() => {
                    // Fallback to view_ticket.php
                    window.location.href = 'view_ticket.php?ticket=' + registration.ticket_number;
                });
        }

        function isEventRegistered(eventId) {
            return registeredEvents.some(e => e.id == eventId);
        }

        function registerForEvent(eventId) {
            // Check if already registered
            if (isEventRegistered(eventId)) {
                alert('You are already registered for this event!');
                closeEventViewModal();
                return;
            }
            
            // Open registration form modal
            document.getElementById('regEventId').value = eventId;
            document.getElementById('registrationModal').style.display = 'block';
            closeEventViewModal();
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').style.display = 'none';
            document.getElementById('registrationForm').reset();
        }

        function submitRegistration(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const eventId = formData.get('event_id');
            
            // Double check if already registered
            if (isEventRegistered(eventId)) {
                alert('You are already registered for this event!');
                closeRegistrationModal();
                return false;
            }
            
            fetch('register_event.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRegistrationModal();
                    
                    // Update registered events list before redirecting
                    loadRegisteredEvents();
                    
                    // Small delay to ensure list updates, then redirect
                    setTimeout(() => {
                        window.location.href = 'view_ticket.php?ticket=' + data.ticket_number;
                    }, 300);
                } else {
                    if (data.message.includes('Already registered')) {
                        // Refresh registered events list
                        loadRegisteredEvents();
                    }
                    alert('Error: ' + (data.message || 'Failed to register'));
                    closeRegistrationModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while registering');
            });
            
            return false;
        }
        
        function showTicketModal(registrationData, formData, eventId) {
            // Get event details
            const event = allEvents.find(e => e.id == eventId);
            if (!event) return;
            
            // Populate ticket modal
            document.getElementById('ticketEventTitle').textContent = event.title;
            document.getElementById('ticketEventType').textContent = event.event_type;
            document.getElementById('ticketLocation').textContent = event.location || 'To be announced';
            document.getElementById('ticketStudentName').textContent = formData.get('student_name');
            document.getElementById('ticketSection').textContent = formData.get('section');
            document.getElementById('ticketCourse').textContent = formData.get('course');
            
            // Format date and time
            const startDate = new Date(event.start_date);
            const dateStr = startDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const timeStr = startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            document.getElementById('ticketDateTime').textContent = `${dateStr} at ${timeStr}`;
            
            document.getElementById('ticketNumber').textContent = registrationData.ticket_number;
            
            // Generate QR code
            const qrContainer = document.getElementById('ticketQRCode');
            qrContainer.innerHTML = ''; // Clear previous QR code
            new QRCode(qrContainer, {
                text: registrationData.ticket_number,
                width: 200,
                height: 200,
                colorDark: "#1e5ba8",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            
            // Show modal
            document.getElementById('ticketModal').style.display = 'block';
        }
        
        function closeTicketModal() {
            document.getElementById('ticketModal').style.display = 'none';
        }
        
        function printTicket() {
            // Create a new window with just the ticket content
            const ticketContent = document.querySelector('.ticket-card-popup').innerHTML;
            const printWindow = window.open('', '', 'height=800,width=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Event Ticket</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
                        body { font-family: 'Poppins', sans-serif; margin: 20px; }
                        .ticket-card-popup { background: linear-gradient(135deg, #1e5ba8 0%, #2d6bb3 100%); border-radius: 20px; color: white; overflow: hidden; }
                        .ticket-header-popup { background: rgba(255, 255, 255, 0.1); padding: 25px; border-bottom: 2px dashed rgba(255, 255, 255, 0.3); }
                        .university-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
                        .logo-circle { width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 3px; }
                        .university-name { font-size: 18px; font-weight: 700; margin: 0; }
                        .status-badge-popup { background: rgba(255, 255, 255, 0.2); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
                        .event-title-popup { font-size: 20px; font-weight: 700; margin: 10px 0 5px 0; }
                        .event-type-popup { font-size: 14px; opacity: 0.9; margin: 0; }
                        .ticket-body-popup { padding: 25px; }
                        .section-label { font-size: 11px; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
                        .section-value { font-size: 15px; font-weight: 600; }
                        .ticket-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 15px 0; }
                        .qr-section { background: white; padding: 20px; border-radius: 12px; text-align: center; margin: 20px 0; }
                        .ticket-number-display { background: rgba(255, 255, 255, 0.15); padding: 12px 20px; border-radius: 8px; text-align: center; margin: 15px 0; }
                        .ticket-number-label { font-size: 11px; opacity: 0.7; margin-bottom: 5px; }
                        .ticket-number-value { font-size: 22px; font-weight: 700; letter-spacing: 2px; }
                        .attendance-status { background: rgba(255, 152, 0, 0.3); padding: 15px; border-radius: 8px; text-align: center; margin-top: 15px; }
                        .action-buttons, .ticket-close-btn { display: none !important; }
                    </style>
                </head>
                <body>
                    <div class="ticket-card-popup">${ticketContent}</div>
                </body>
                </html>
            `);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
            }, 250);
        }

        function showSuccessPopup() {
            document.getElementById('popupOverlay').classList.add('show');
            document.getElementById('successPopup').classList.add('show');
            setTimeout(closeSuccessPopup, 2000);
        }

        function closeSuccessPopup() {
            document.getElementById('popupOverlay').classList.remove('show');
            document.getElementById('successPopup').classList.remove('show');
        }

        function viewEventDetails(eventId) {
            // Reload registered events first to get latest data
            loadRegisteredEvents().then(() => {
                const event = allEvents.find(e => e.id == eventId);
                if (!event) return;

                const isRegistered = isEventRegistered(eventId);
                
                document.getElementById('viewEventTitle').textContent = event.title;
                document.getElementById('viewEventDetails').innerHTML = `
                    <p><strong>Type:</strong> ${event.event_type}</p>
                    <p><strong>Date:</strong> ${formatEventDate(event.start)}</p>
                    <p><strong>Time:</strong> ${formatTime(event.start)} - ${formatTime(event.end)}</p>
                    <p><strong>Location:</strong> ${event.location || 'TBA'}</p>
                    <p><strong>Description:</strong> ${event.description || 'No description'}</p>
                `;
                
                const ticketNumber = registeredEvents.find(e => e.id == eventId)?.ticket_number;
                
                document.getElementById('viewEventActions').innerHTML = isRegistered
                    ? `<button class="btn-registered"><i class="fas fa-check"></i> Already Registered</button>
                       ${ticketNumber ? `<button class="btn-register" style="margin-top: 10px;" onclick="window.location.href='view_ticket.php?ticket=${ticketNumber}'"><i class="fas fa-ticket-alt"></i> View Ticket</button>` : ''}`
                    : `<button class="btn-register" onclick="registerForEvent(${eventId})">Pre-Register</button>`;
                
                document.getElementById('eventViewModal').style.display = 'block';
            });
        }

        function closeEventViewModal() {
            document.getElementById('eventViewModal').style.display = 'none';
        }

    </script>
</body>
</html>
