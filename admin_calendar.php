<?php
session_start();
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
            <button class="create-btn" onclick="openEventModal()">
                <i class="fas fa-plus"></i> CREATE
            </button>

            <div class="mini-calendar">
                <div class="mini-calendar-header">
                    <button class="mini-nav-btn" onclick="prevMonth()"><i class="fas fa-chevron-left"></i></button>
                    <span class="mini-month-year">January 2025</span>
                    <button class="mini-nav-btn" onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="mini-calendar-grid">
                    <div class="mini-day-header">S</div>
                    <div class="mini-day-header">M</div>
                    <div class="mini-day-header">T</div>
                    <div class="mini-day-header">W</div>
                    <div class="mini-day-header">T</div>
                    <div class="mini-day-header">F</div>
                    <div class="mini-day-header">S</div>
                    <div class="mini-day inactive">29</div>
                    <div class="mini-day inactive">30</div>
                    <div class="mini-day inactive">31</div>
                    <div class="mini-day">1</div>
                    <div class="mini-day">2</div>
                    <div class="mini-day">3</div>
                    <div class="mini-day">4</div>
                    <div class="mini-day">5</div>
                    <div class="mini-day">6</div>
                    <div class="mini-day">7</div>
                    <div class="mini-day">8</div>
                    <div class="mini-day">9</div>
                    <div class="mini-day">10</div>
                    <div class="mini-day">11</div>
                    <div class="mini-day">12</div>
                    <div class="mini-day">13</div>
                    <div class="mini-day">14</div>
                    <div class="mini-day">15</div>
                    <div class="mini-day">16</div>
                    <div class="mini-day">17</div>
                    <div class="mini-day">18</div>
                    <div class="mini-day">19</div>
                    <div class="mini-day">20</div>
                    <div class="mini-day">21</div>
                    <div class="mini-day">22</div>
                    <div class="mini-day">23</div>
                    <div class="mini-day">24</div>
                    <div class="mini-day">25</div>
                    <div class="mini-day">26</div>
                    <div class="mini-day">27</div>
                    <div class="mini-day">28</div>
                    <div class="mini-day active">29</div>
                    <div class="mini-day">30</div>
                    <div class="mini-day">31</div>
                </div>
            </div>

            <div class="meet-with-section">
                <h3>Meet with...</h3>
                <div class="search-box">
                    <i class="fas fa-user-friends"></i>
                    <input type="text" placeholder="Search for people">
                </div>
            </div>

            <div class="time-insights">
                <h3>Time Insights <i class="fas fa-chevron-up"></i></h3>
                <p class="date-range">JAN 1-31 2025</p>
                <p class="meeting-time">0hr in meeting</p>
                <a href="#" class="more-insights"><i class="fas fa-chart-line"></i> MORE INSIGHTS</a>
            </div>

            <div class="upcoming-events">
                <h3>Upcoming Events</h3>
                <div class="event-item">
                    <div class="event-color-bar events"></div>
                    <div class="event-details">
                        <p class="event-title">Freshmen Week</p>
                        <p class="event-time">Oct 1-5, 2024 | Public Time</p>
                    </div>
                </div>
                <div class="event-item">
                    <div class="event-color-bar events"></div>
                    <div class="event-details">
                        <p class="event-title">Posisenation 2015</p>
                        <p class="event-time">Oct 14-17, 2024 | Public Time</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="calendar-main">
            <div class="calendar-header">
                <h2>University Schedule Week</h2>
                <div class="calendar-controls">
                    <button class="schedule-btn">
                        <i class="fas fa-calendar-week"></i> Schedule <i class="fas fa-chevron-down"></i>
                    </button>
                    <button class="date-btn">
                        <i class="fas fa-calendar"></i> JAN 14(SUN) Public Time <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <div class="week-view">
                <div class="week-header">
                    <div class="time-column"></div>
                    <div class="day-column">Mon<br><span class="day-number">02</span></div>
                    <div class="day-column">Tues<br><span class="day-number">03</span></div>
                    <div class="day-column">Wed<br><span class="day-number">04</span></div>
                    <div class="day-column">Thu<br><span class="day-number">05</span></div>
                    <div class="day-column">Fri<br><span class="day-number">06</span></div>
                    <div class="day-column">Sat<br><span class="day-number">07</span></div>
                </div>

                <div class="week-grid">
                    <div class="time-column">
                        <div class="time-slot">06:00</div>
                        <div class="time-slot">09:00</div>
                        <div class="time-slot">12:00</div>
                        <div class="time-slot">15:00</div>
                        <div class="time-slot">18:00</div>
                    </div>
                    <div class="day-column-grid"></div>
                    <div class="day-column-grid"></div>
                    <div class="day-column-grid"></div>
                    <div class="day-column-grid"></div>
                    <div class="day-column-grid"></div>
                    <div class="day-column-grid"></div>
                </div>
            </div>

            <div class="event-filters">
                <button class="filter-btn active">Events</button>
                <button class="filter-btn">One by One</button>
                <button class="filter-btn">Webinar</button>
                <button class="filter-btn">Seminars</button>
                <button class="filter-btn">Workshop</button>
            </div>

            <div class="view-actions">
                <button class="view-btn active-view">WEEK VIEW</button>
                <button class="view-btn">UPDATE UNIVERSITY CALENDAR</button>
            </div>
        </main>

        <aside class="calendar-sidebar-right">
            <div class="search-events-box">
                <input type="text" placeholder="SEARCH EVENTS" class="search-events-input">
                <i class="fas fa-search"></i>
            </div>
            <button class="view-university-btn">VIEW UNIVERSITY CALENDAR</button>
        </aside>
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

    <script src="calendar_admin.js"></script>
</body>
</html>
