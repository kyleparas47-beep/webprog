<?php
session_start();
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
                <a href="#" class="nav-link">VIEW EVENTS</a>
            </nav>

            <div class="header-icons">
                <button onclick="window.location.href='logout.php'" class="icon-btn user-icon">
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
            </div>
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
        <div class="icon">✅</div>
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
                            start: new Date(event.start_date),
                            end: new Date(event.end_date)
                        }));
                        renderCalendar();
                        displayUpcomingEvents();
                    }
                })
                .catch(error => console.error('Error loading events:', error));
        }

        function loadRegisteredEvents() {
            fetch('get_registered_events.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        registeredEvents = data.events;
                        displayRegisteredEvents();
                    }
                })
                .catch(error => console.error('Error loading registered events:', error));
        }

        function displayRegisteredEvents() {
            const container = document.getElementById('registeredEventsList');
            if (registeredEvents.length === 0) {
                container.innerHTML = '<p style="color: #999; text-align: center;">No registered events yet</p>';
                return;
            }

            let html = '';
            registeredEvents.forEach(event => {
                html += `<div class="registered-event-item">
                            <div class="registered-event-title">${event.title}</div>
                            <div class="registered-event-date">
                                <i class="fas fa-calendar"></i> ${formatEventDate(new Date(event.start_date))}
                            </div>
                            <div class="registered-event-date">
                                <i class="fas fa-clock"></i> ${formatTime(new Date(event.start_date))}
                            </div>
                        </div>`;
            });
            container.innerHTML = html;
        }

        function isEventRegistered(eventId) {
            return registeredEvents.some(e => e.id == eventId);
        }

        function registerForEvent(eventId) {
            fetch('register_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'event_id=' + eventId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessPopup();
                    loadRegisteredEvents();
                    closeEventViewModal();
                } else {
                    alert('Error: ' + (data.message || 'Failed to register'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while registering');
            });
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
            
            document.getElementById('viewEventActions').innerHTML = isRegistered
                ? '<button class="btn-registered"><i class="fas fa-check"></i> Already Registered</button>'
                : `<button class="btn-register" onclick="registerForEvent(${eventId})">Pre-Register</button>`;
            
            document.getElementById('eventViewModal').style.display = 'block';
        }

        function closeEventViewModal() {
            document.getElementById('eventViewModal').style.display = 'none';
        }

        // Include all the calendar rendering functions from calendar_functions.js
        // The functions are already included above in the script
    </script>
</body>
</html>
