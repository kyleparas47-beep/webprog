# National University Events Calendar

## Overview

This is a web-based events calendar system for National University that allows administrators to create and manage campus events (webinars, seminars, workshops) while students can view upcoming events. The application uses a PHP backend with MySQL database (via XAMPP) and vanilla JavaScript for frontend interactivity.

The system features role-based access control where administrators have full event management capabilities including creating, editing, and deleting events, while students have read-only access to view events.

**Latest Update (Oct 2025)**: Fully functional Google Calendar-style calendar with Month/Week/Day views, event search, filtering, and complete CRUD operations.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Frontend Architecture

**Technology Stack**: Vanilla HTML, CSS, and JavaScript (no frameworks)

The frontend uses a multi-page application (MPA) architecture with separate pages for different user roles:

- **Login/Registration System** (`index.html`, `style.css`, `script.js`) - Form-based authentication with toggle between login and signup views
- **Student Dashboard** (`student_page.php`, `student.css`) - Read-only event viewing interface with scrolling marquee banner
- **Admin Dashboard** (`admin_page.php`) - Event management interface with calendar access and scrolling marquee banner
- **Admin Calendar** (`admin_calendar.php`, `calendar_admin.css`, `calendar_admin.js`) - Full-featured Google Calendar-style interface with Month/Week/Day views
- **Profile/Logout** (`logout.html`, `logout.css`) - User profile and session management

**Design Pattern**: Each page is self-contained with dedicated CSS files. JavaScript modules handle specific functionality (authentication, calendar operations, event modals).

**Calendar Implementation** (`calendar_admin.js` - 500+ lines):
- **Multiple Views**: Month view (grid), Week view (hourly slots), Day view (detailed timeline)
- **Date Navigation**: Previous/Next buttons, Today button, mini calendar for date jumping
- **Event Search**: Real-time search across title, description, and location
- **Event Filtering**: Filter by type (All Events, Webinar, Seminars, Workshop) using data attributes
- **Click-to-Create**: Click any time slot in Week/Day view to create events at that specific time
- **Multi-Day Events**: Proper rendering of events spanning multiple days
- **Event CRUD**: Modal-based create/edit/delete operations via AJAX
- **Color Coding**: Visual distinction by event type (Blue/Orange/Red/Green)
- **Responsive Design**: Grid layouts that adapt to different views

### Backend Architecture

**Technology Stack**: PHP with procedural programming approach

**Session Management**: PHP sessions for user authentication and role-based access control

**Event Management Operations**:
- `add_event.php` - Creates new events (admin only)
- `edit_event.php` - Updates existing events (admin only)
- `delete_event.php` - Removes events (admin only)
- `get_events.php` - Fetches all events for calendar display
- All operations return JSON responses for AJAX interactions

**Access Control**: Role-based permissions separating admin and student capabilities. Students cannot access admin calendar (`admin_calendar.php`) or event modification endpoints.

### Data Storage

**Database**: MySQL via XAMPP (phpMyAdmin)

**Database Name**: `student_db`

**Events Table Schema** (from `database_setup.sql`):
```
- id: Auto-increment primary key
- title: VARCHAR - Event title
- description: TEXT - Event description  
- event_type: ENUM/VARCHAR - Type (Events, Webinar, Seminars, Workshop)
- start_date: DATETIME - Event start date and time
- end_date: DATETIME - Event end date and time
- location: VARCHAR - Event location
- created_by: INT - Foreign key to student table (user ID)
- created_at: TIMESTAMP - Creation timestamp
```

**Relationships**: Events are linked to users through `created_by` foreign key referencing the `student` table.

**Data Flow**: PHP endpoints fetch/modify database records and return JSON for frontend consumption. Frontend JavaScript renders events on calendar and lists.

### Authentication & Authorization

**Authentication Method**: Session-based authentication via PHP sessions

**User Roles**: 
- **Admin**: Full CRUD access to events, access to admin calendar
- **Student**: Read-only access to view events

**Role Enforcement**: 
- Page-level access control (students redirected from admin pages)
- Server-side validation on event modification endpoints
- Frontend UI adapts based on user role (students see only "See Current Events" button, admins see "+ add events >" link)

**Session Data**: User ID stored in session, used to populate `created_by` field when creating events

## Recent Changes (October 2025)

### Google Calendar-Style Implementation
- ✅ Built comprehensive calendar system with Month/Week/Day views
- ✅ Implemented date navigation (Previous/Next/Today buttons)
- ✅ Added mini calendar for quick date selection
- ✅ Created event search functionality (real-time filtering)
- ✅ Implemented type-based filtering (All/Webinar/Seminars/Workshop)
- ✅ Added click-to-create events on time slots
- ✅ Fixed view switching with proper button parameter passing
- ✅ Fixed filter functionality with data-filter attributes
- ✅ Fixed multi-day event rendering across dates
- ✅ Enhanced event positioning calculations for proper display
- ✅ Added upcoming events sidebar
- ✅ Implemented color-coded event cards by type
- ✅ Created comprehensive documentation (README_CALENDAR.md)

### Bug Fixes
- Fixed view switching ReferenceError (changeView now accepts button parameter)
- Fixed filter text normalization (using data-filter attributes)
- Fixed multi-day events disappearing after first day (enhanced calculateEventPosition logic)

## External Dependencies

### Third-Party Services

**None** - This is a self-contained application without external API integrations

### Development Environment

**XAMPP Stack**:
- Apache web server for PHP execution
- MySQL database for data persistence
- phpMyAdmin for database management

**Local Development**: Application designed to run on localhost via XAMPP

**Replit Environment**: PHP 8.2 development server on port 5000

### Frontend Libraries

**Google Fonts**: Inter and Poppins font families loaded via CDN for typography

**No JavaScript Frameworks**: Pure vanilla JavaScript implementation without React, Vue, or other frameworks

### Assets

**Images**: 
- Login background: `assets/LOG IN UI (2).jpg`
- Random user avatars via `randomuser.me` API for profile pictures (demo purposes)

**Icons**: Unicode/emoji characters used for UI icons (no icon library dependency)

## Project Structure

```
/
├── index.html              # Login/registration page
├── style.css               # Login page styles
├── script.js               # Login authentication logic
├── admin_page.php          # Admin dashboard
├── student_page.php        # Student dashboard  
├── student.css             # Student page styles
├── admin_calendar.php      # Google Calendar-style admin calendar
├── calendar_admin.js       # Calendar logic (500+ lines)
├── calendar_admin.css      # Calendar styles
├── logout.html             # Logout page
├── logout.css              # Logout styles
├── add_event.php           # Create event API
├── edit_event.php          # Update event API
├── delete_event.php        # Delete event API
├── get_events.php          # Fetch events API
├── config.php              # Database configuration
├── database_setup.sql      # Database schema
├── README_CALENDAR.md      # Calendar user guide
└── assets/                 # Images and media
```

## Key Features

### Admin Features
- ✅ Full CRUD operations for events
- ✅ Google Calendar-style interface with Month/Week/Day views
- ✅ Click-to-create events on calendar grid
- ✅ Real-time event search
- ✅ Event type filtering
- ✅ Date navigation and mini calendar
- ✅ Color-coded event display
- ✅ Upcoming events sidebar
- ✅ Multi-day event support

### Student Features
- ✅ View-only access to events
- ✅ Scrolling marquee banner
- ✅ "See Current Events" button
- ✅ Identical design to admin page (without creation capability)

## Technical Highlights

### JavaScript Calendar Features
- Dynamic view rendering (Month/Week/Day)
- Event positioning based on start time and duration
- Multi-day event handling with proper spanning
- Search functionality with live filtering
- Type-based filtering with data attributes
- Click handlers for time slot event creation
- Modal-based event management
- Mini calendar with month navigation

### CSS Grid Layouts
- Month view: 7-column grid (days) × 6-row grid (weeks)
- Week view: 7-column grid with hourly time slots
- Day view: Single column with 24-hour timeline
- Responsive event cards
- Color-coded event types

### Backend API Design
- RESTful-style endpoints for CRUD operations
- JSON response format
- Session-based authentication
- Prepared SQL statements for security
- Error handling with proper HTTP responses
