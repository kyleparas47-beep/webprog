# National University Events Calendar

## Overview

This is a web-based events calendar system for National University that allows administrators to create and manage campus events (webinars, seminars, workshops) while students can view upcoming events. The application uses a PHP backend with MySQL database (via XAMPP) and vanilla JavaScript for frontend interactivity.

The system features role-based access control where administrators have full event management capabilities including creating, editing, and deleting events, while students have read-only access to view events.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Frontend Architecture

**Technology Stack**: Vanilla HTML, CSS, and JavaScript (no frameworks)

The frontend uses a multi-page application (MPA) architecture with separate pages for different user roles:

- **Login/Registration System** (`index.html`, `style.css`, `script.js`) - Form-based authentication with toggle between login and signup views
- **Student Dashboard** (`student_page.php`, `student.css`) - Read-only event viewing interface
- **Admin Dashboard** (`admin_page.php`) - Event management interface with calendar access
- **Admin Calendar** (`admin_calendar.php`, `calendar_admin.css`, `calendar_admin.js`) - Full-featured calendar with create/edit/delete capabilities
- **Profile/Logout** (`logout.html`, `logout.css`) - User profile and session management

**Design Pattern**: Each page is self-contained with dedicated CSS files. JavaScript modules handle specific functionality (authentication, calendar operations, event modals).

**Calendar Implementation** (`scriptcal.js`):
- Week view with time slots
- Mini calendar for date navigation
- Event filtering by type (Events, Webinar, Seminars, Workshop)
- Modal-based event creation/editing
- AJAX-based event operations (add, edit, delete)

### Backend Architecture

**Technology Stack**: PHP with procedural programming approach

**Session Management**: PHP sessions for user authentication and role-based access control

**Event Management Operations**:
- `add_event.php` - Creates new events (admin only)
- `edit_event.php` - Updates existing events (admin only)
- Event operations expect JSON responses for AJAX interactions

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

## External Dependencies

### Third-Party Services

**None** - This is a self-contained application without external API integrations

### Development Environment

**XAMPP Stack**:
- Apache web server for PHP execution
- MySQL database for data persistence
- phpMyAdmin for database management

**Local Development**: Application designed to run on localhost via XAMPP

### Frontend Libraries

**Google Fonts**: Inter and Poppins font families loaded via CDN for typography

**No JavaScript Frameworks**: Pure vanilla JavaScript implementation without React, Vue, or other frameworks

### Assets

**Images**: 
- Login background: `assets/LOG IN UI (2).jpg`
- Random user avatars via `randomuser.me` API for profile pictures (demo purposes)

**Icons**: Unicode/emoji characters used for UI icons (no icon library dependency)