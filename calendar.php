<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Schedule - Events Calendar</title>
    <link rel="stylesheet" href="stylescal.css">
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <div class="logo">
                <span class="logo-icon">NU</span>
                <span class="logo-text">National University</span>
            </div>
        </div>
        <nav class="header-nav">
            <a onclick="window.location.href='student_page.php'" class="nav-link">HOME</a>
            <span class="nav-separator">|</span>
            <a href="#" class="nav-link active">CALENDAR</a>
            <span class="nav-separator">|</span>
            <a href="#" class="nav-link">ADMIN</a>
        </nav>
        <div class="header-right">
            <button class="user-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>
            <button class="menu-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <button class="create-btn" id="createEventBtn">
                <span class="plus-icon">+</span>
                CREATE
            </button>

            <div class="mini-calendar">
                <div class="mini-calendar-header">
                    <button class="nav-btn" id="prevMonth">&lt;</button>
                    <span class="month-year" id="miniMonthYear"></span>
                    <button class="nav-btn" id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-grid" id="miniCalendar"></div>
            </div>

            <div class="meet-with">
                <h3>Meet with...</h3>
                <div class="search-box">
                    <input type="text" placeholder="Search for people">
                </div>
            </div>

            <div class="time-insights">
                <div class="insights-header">
                    <h3>Time insights</h3>
                    <button class="toggle-btn">^</button>
                </div>
                <div class="insights-content">
                    <p class="date-range" id="insightsDateRange"></p>
                    <p class="status">0Hr in meeting</p>
                    <button class="more-insights-btn">MORE INSIGHTS</button>
                </div>
            </div>

            <div class="upcoming-events">
                <h3>Upcoming Events</h3>
                <div id="upcomingEventsList"></div>
            </div>
        </aside>

        <main class="main-content">
            <div class="schedule-header">
                <h1>University Schedule Week</h1>
                <div class="schedule-controls">
                    <button class="schedule-dropdown">
                        <span>Schedule</span>
                        <span class="dropdown-icon">▼</span>
                    </button>
                    <button class="view-btn active">Week</button>
                    <button class="refresh-btn">↻</button>
                </div>
            </div>

            <div class="date-navigation">
                <button class="nav-arrow" id="prevWeek">&lt;</button>
                <span class="date-range" id="weekDateRange"></span>
                <button class="nav-arrow" id="nextWeek">&gt;</button>
                <span class="timezone" id="timezoneDisplay"></span>
            </div>

            <div class="search-box-main">
                <input type="text" placeholder="search events" id="searchEvents">
            </div>

            <div class="schedule-grid" id="scheduleGrid">
                <div class="time-column" id="timeColumn"></div>
                <div class="days-container" id="daysContainer"></div>
            </div>

            <div class="event-filters">
                <button class="filter-btn active" data-filter="all">Events</button>
                <button class="filter-btn" data-filter="one-by-one">One by One</button>
                <button class="filter-btn" data-filter="webinar">Webinar</button>
                <button class="filter-btn" data-filter="seminars">Seminars</button>
                <button class="filter-btn" data-filter="workshop">Workshop</button>
            </div>

            <div class="action-buttons">
                <button class="save-btn">SAVE UPDATES</button>
                <button class="update-btn">UPDATE UNIVERSITY CALENDAR</button>
            </div>
        </main>
    </div>

    <div class="modal" id="eventModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Create Event</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="eventForm">
                <input type="hidden" id="eventId" name="eventId">
                
                <div class="form-group">
                    <label for="eventTitle">Event Title</label>
                    <input type="text" id="eventTitle" name="title" required>
                </div>

                <div class="form-group">
                    <label for="eventType">Event Type</label>
                    <select id="eventType" name="type" required>
                        <option value="events">Events</option>
                        <option value="one-by-one">One by One</option>
                        <option value="webinar">Webinar</option>
                        <option value="seminars">Seminars</option>
                        <option value="workshop">Workshop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="eventDate">Date</label>
                    <input type="date" id="eventDate" name="date" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="eventStartTime">Start Time</label>
                        <input type="time" id="eventStartTime" name="startTime" required>
                    </div>
                    <div class="form-group">
                        <label for="eventEndTime">End Time</label>
                        <input type="time" id="eventEndTime" name="endTime" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="eventDescription">Description</label>
                    <textarea id="eventDescription" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="eventLocation">Location</label>
                    <input type="text" id="eventLocation" name="location">
                </div>

                <div class="modal-actions">
                    <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                    <button type="submit" class="submit-btn">Save Event</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
